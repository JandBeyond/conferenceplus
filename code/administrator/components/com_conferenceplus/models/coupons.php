<?php
/**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2014 JandBeyond
 * @license    GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die;

require_once 'default.php';

/**
 * Class ConferenceplusModelCoupons
 *
 * @since  1.0
 */
class ConferenceplusModelCoupons extends ConferenceplusModelDefault
{

	use Conferenceplus\Date\Helper;

	/**
	 * Class Constructor
	 *
	 * @param   array  $config  Configuration array
	 */
	public function __construct($config = array())
	{
		if (!isset($config['behaviors']) && FOFPlatform::getInstance()->isFrontend())
		{
			$config['behaviors'] = array('filters', 'access', 'enabled');
		}

		parent::__construct($config);
	}

	/**
	 * Check the avaliblity of coupons for a tickettype
	 *
	 * @param   integer  $tickettypeId  the tickettype id
	 *
	 * @return bool
	 */
	public function isAvailable($tickettypeId)
	{
		$result  = false;
		$coupons = $this->getAssignedCoupons($tickettypeId, true);
		reset($coupons);

		while ((list($key, $coupon) = each($coupons)) && !$result)
		{
			$result = $this->couponValid($coupon);
		}

		return $result;
	}

	/**
	 * Check if a coupon is vaild
	 *
	 * @param   object  $coupon  a coupon
	 *
	 * @return bool
	 */
	private function couponValid($coupon)
	{
		// Check the date
		$now   = JFactory::getDate();

		if ($now < $coupon->start || $now > $coupon->end)
		{
			return false;
		}

		if ($coupon->number_valid_items != 0 && $coupon->number_valid_items <= $coupon->used)
		{
			return false;
		}

		return true;
	}

	/**
	 * Check the avalibility of a coupon and calculates the discount
	 *
	 * @param   integer  $couponIdentifier  the coupon id
	 * @param   integer  $tickettypeId      the tickettype id
	 *
	 * @return array
	 */
	public function checkCouponAndTicket($couponIdentifier, $tickettypeId)
	{
		$couponTable = $this->getCouponByIdentifier($couponIdentifier);

		if ($couponTable === false)
		{
			// Coupon invalid at all
			return $this->buildReturnValue(0, 0, 0);
		}

		$couponId = $couponTable->conferenceplus_coupon_id;

		$tickettypeTable = FOFTable::getAnInstance('tickettypes');
		$result = $tickettypeTable->load($tickettypeId);

		if ($result !== true)
		{
			// Tickettype invalid at all
			return $this->buildReturnValue(0, 0, 0);
		}

		$fee = $tickettypeTable->fee;

		$assignedTickettypes = $this->getAssignedTickettypes($couponId);

		if (count($assignedTickettypes) == 0)
		{
			// Coupon is not valid for this tickettype
			return $this->buildReturnValue(1, $fee, $fee);
		}

		$coupon = $this->getAssignedCoupons($tickettypeId, true, $couponId)[0];

		$valid = $this->couponValid($coupon);

		if ( ! $valid)
		{
			// Coupon not valid because of date or usage
			return $this->buildReturnValue(2, $fee, $fee);
		}

		if ($coupon->number_valid_items != 0 && $coupon->number_valid_items <= $coupon->temp_assigned)
		{
			// Coupon not valid because all are used at the moment
			return $this->buildReturnValue(3, $fee, $fee);
		}

		// Great coupon can be used, calculate discount
		$discounted = $this->calculateDiscountedFee($coupon, $fee);

		return $this->buildReturnValue(99, $fee, $discounted);
	}

	/**
	 * Calculate the discounted fee for a fee based on a coupon
	 *
	 * @param   mixed    $coupon  the coupon
	 * @param   integer  $fee     the original fee for a ticket
	 *
	 * @return int
	 */
	private function calculateDiscountedFee($coupon, $fee)
	{
		if ($coupon->freeticket == 1)
		{
			return 0;
		}

		if ($coupon->fixed_fee != 0)
		{
			return $coupon->fixed_fee;
		}

		if ($coupon->discount_fix != 0)
		{
			return ($fee - $coupon->discount_fix);
		}

		if ($coupon->discount_percentaged != 0)
		{
			return $fee - (int) ($fee / 100 * $coupon->discount_percentaged);
		}

		return $fee;
	}

	/**
	 * Build the return value
	 *
	 * @param   integer  $type        returnType
	 * @param   integer  $fee         the original fee
	 * @param   integer  $discounted  the dicounted fee
	 *
	 * @return array
	 */
	private function buildReturnValue($type, $fee, $discounted)
	{
		return ['returnType' => $type, 'fee' => $fee, 'discounted' => $discounted];
	}

	/**
	 * set a coupon as temporary used by this ticket
	 *
	 * Note: A cron job should check regulary if the coupon was finally used or not
	 *
	 * @param   integer  $couponIdentifier  the coupon id
	 * @param   integer  $ticketId          the coupon id
	 *
	 * @return bool
	 */
	public function setTemporaryUsed($couponIdentifier, $ticketId)
	{
		$couponTable = $this->getCouponByIdentifier($couponIdentifier);

		if ($couponTable === false)
		{
			// That should never happen, only here because of paranoia
			return false;
		}

		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$now   = $db->q(JFactory::getDate()->toSql());

		$query->insert('#__conferenceplus_coupons_inuse')
			->columns(
				[
					$db->quoteName('coupon_id'),
					$db->quoteName('ticket_id'),
					$db->quoteName('created')
				]
			)
			->values(
				(int) $couponTable->conferenceplus_coupon_id . ', ' .
				(int) $ticketId . ', ' .
				$now
			);

		$db->setQuery($query);

		if ( ! $db->execute())
		{
			return false;
		}

		return true;
	}

	/**
	 * set a coupon as used by a ticket
	 *
	 * @param   integer  $ticketId   the coupon id
	 * @param   integer  $paymentId  the coupon id
	 *
	 * @return bool
	 */
	public function setUsed($ticketId, $paymentId)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$now   = $db->q(JFactory::getDate()->toSql());

		$query->update('#__conferenceplus_coupons_inuse')
			->set($db->quoteName('payment_id') . '=' . $paymentId)
			->set($db->quoteName('modified') . '=' . $now)
			->where($db->quoteName('ticket_id') . '=' . (int) $ticketId);

		$db->setQuery($query);

		if ( ! $db->execute())
		{
			return false;
		}

		return true;
	}

	/**
	 * Add some values to the list result
	 *
	 * @param   array  &$resultArray  An array of objects, each row representing a record
	 *
	 * @return  void
	 */
	protected function onProcessList(&$resultArray)
	{
		$tempUsed = $this->getTempUsedCoupons();
		$used     = $this->getUsedCoupons();

		foreach ($resultArray as &$item)
		{
			$id = $item->conferenceplus_coupon_id;
			$item->used = array_key_exists($id, $used) ? $used[$id]->num : 0;
			$item->temp_assigned = array_key_exists($id, $tempUsed) ? $tempUsed[$id]->num : 0;
		}
	}

	/**
	 * get the temporary assigned Coupons
	 *
	 * @return mixed
	 */
	private function getTempUsedCoupons()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('coupon_id, count(*) as num')
			->from('#__conferenceplus_coupons_inuse')
			->where($db->qn('ticket_id') . ' <> 0')
			->where($db->qn('payment_id') . ' = 0')
			->group('coupon_id');

		$db->setQuery($query);

		return $db->loadObjectList('coupon_id');

	}

	/**
	 * get the user Coupons
	 *
	 * @return mixed
	 */
	private function getUsedCoupons()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('coupon_id, count(*) as num')
			->from('#__conferenceplus_coupons_inuse')
			->where($db->qn('ticket_id') . ' <> 0')
			->where($db->qn('payment_id') . ' <> 0')
			->group('coupon_id');

		$db->setQuery($query);

		return $db->loadObjectList('coupon_id');

	}

	/**
	 * This method runs after an item has been gotten from the database in a read
	 * operation. You can modify it before it's returned to the MVC triad for
	 * further processing.
	 *
	 * @param   FOFTable  &$record  The table instance we fetched
	 *
	 * @return  void
	 */
	protected function onAfterGetItem(&$record)
	{
		parent::onAfterGetItem($record);

		if (FOFPlatform::getInstance()->isBackend())
		{
			if ( ! is_null($record))
			{
				$record->assignedTickettypes = $this->getAssignedTickettypes($record->conferenceplus_coupon_id);

				$record->tickettypes = $this->getTickettypes();
			}
		}
	}

	/**
	 * This method runs before the $data is saved to the $table. Return false to
	 * stop saving.
	 *
	 * @param   array     &$data   The data to save
	 * @param   FOFTable  &$table  The table to save the data to
	 *
	 * @return  boolean  Return false to prevent saving, true to allow it
	 */
	protected function onBeforeSave(&$data, &$table)
	{
		if (!parent::onBeforeSave($data, $table))
		{
			return false;
		}

		if ( ! $this->manageDateFields($data, ['s', 'e'], ['start', 'end']))
		{
			return false;
		}

		return true;
	}

	/**
	 * This method runs after the data is saved to the $table.
	 *
	 * @param   FOFTable  &$table  The table which was saved
	 *
	 * @return  boolean
	 */
	protected function onAfterSave(&$table)
	{
		if ( ! parent::onAfterSave($table))
		{
			return false;
		}

		if (FOFPlatform::getInstance()->isFrontend())
		{
			return true;
		}

		$this->assignCouponToTickettypes($table);

		return true;
	}

	/**
	 * This method runs after the data is saved to the $table.
	 *
	 * @param   FOFTable  $table  The table which was saved
	 *
	 * @return  void
	 */
	private function assignCouponToTickettypes($table)
	{
		$input = $this->input;

		$coupon_id = $table->conferenceplus_coupon_id;

		$savedAssignedTickettypes = $this->getAssignedTickettypes($coupon_id);

		$assignedTickettypes = [];

		foreach ($this->getTickettypes() as $tickettype)
		{
			$tag = 'tickettype_' . $tickettype->conferenceplus_tickettype_id;

			if ($input->get($tag, 0) != 0 )
			{
				$assignedTickettypes[] = $tickettype->conferenceplus_tickettype_id;
			}
		}

		$this->assignTickettypes(array_diff($assignedTickettypes, $savedAssignedTickettypes), $coupon_id);
		$this->unassignTickettypes(array_diff($savedAssignedTickettypes, $assignedTickettypes), $coupon_id);

		return true;
	}

	/**
	 * Assign a coupon to tickettypes
	 *
	 * @param   array    $items     the data
	 * @param   integer  $couponId  the id of the coupon
	 *
	 * @return bool
	 */
	private function assignTickettypes($items, $couponId)
	{
		if ( ! empty($items))
		{
			$db    = $this->getDbo();
			$query = $db->getQuery(true);
			$now   = $db->q(JFactory::getDate()->toSql());

			$query->insert('#__conferenceplus_coupons_to_tickettypes')
				->columns(
					[
						$db->quoteName('coupon_id'),
						$db->quoteName('tickettype_id'),
						$db->quoteName('created')
					]
				);

			foreach ($items as $item)
			{
				$query->values(
					(int) $couponId . ', ' .
					(int) $item . ', ' .
					$now
				);
			}

			$db->setQuery($query);

			if ( ! $db->execute())
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Unassign a coupon to tickettypes
	 *
	 * @param   array    $items     the data
	 * @param   integer  $couponId  the id of the coupon
	 *
	 * @return bool
	 */
	private function unassignTickettypes($items, $couponId)
	{
		$result = true;

		if ( ! empty($items))
		{
			foreach ($items AS $item)
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->delete('#__conferenceplus_coupons_to_tickettypes')
					->where('coupon_id =' . (int) $couponId)
					->where('tickettype_id = ' . (int) $item);

				$db->setQuery($query);

				if ( ! $db->execute())
				{
					$result = false;
				}
			}
		}

		return $result;
	}

	/**
	 * Get assigned tickettypes for a coupon
	 *
	 * @param   integer  $couponId  the couponId
	 *
	 * @return  mixed
	 */
	private function getAssignedTickettypes($couponId)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('tickettype_id')
			->from('#__conferenceplus_coupons_to_tickettypes')
			->where($db->qn('coupon_id') . ' = ' . $db->q($couponId));

		$db->setQuery($query);
		$result = $db->loadColumn();

		return $result;
	}

	/**
	 * Get assigned coupons for a tickettype
	 *
	 * @param   integer  $tickettypeId  the tickettypeId
	 * @param   bool     $onlyEnabled   only enabled coupons
	 * @param   string   $coupon        a coupon code
	 *
	 * @return  mixed
	 */
	private function getAssignedCoupons($tickettypeId, $onlyEnabled=false, $coupon=null)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('coupon.*')
			->from('#__conferenceplus_coupons_to_tickettypes as rel')
			->join('INNER', '#__conferenceplus_coupons as coupon ON coupon.conferenceplus_coupon_id = rel.coupon_id')
			->where($db->qn('rel.tickettype_id') . ' = ' . $db->q($tickettypeId));

		if ($onlyEnabled)
		{
			$query->where($db->qn('coupon.enabled') . ' = 1');
		}

		if ( ! is_null($coupon))
		{
			$query->where($db->qn('coupon.conferenceplus_coupon_id') . ' = ' . $db->q($coupon));
		}

		$db->setQuery($query);
		$result = $db->loadObjectList();

		$tempUsed = $this->getTempUsedCoupons();
		$used     = $this->getUsedCoupons();

		foreach ($result as &$r)
		{
			$id = $r->conferenceplus_coupon_id;
			$r->used = array_key_exists($id, $used) ? $used[$id]->num : 0;
			$r->temp_assigned = array_key_exists($id, $tempUsed) ? $tempUsed[$id]->num : 0;
		}

		return $result;
	}

	/**
	 * Get tickettypes
	 *
	 * @param   integer  $eventId  the event id
	 *
	 * @return  mixed
	 */
	public function getTickettypes($eventId=null)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('*, e.name as eventname')
			->from('#__conferenceplus_tickettypes as tt')
			->join('INNER', '#__conferenceplus_events as e ON tt.event_id = e.conferenceplus_event_id');

		if ( ! is_null($eventId))
		{
			$query->where($db->qn('tt.event_id') . ' = ' . $db->q($eventId));
		}

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get a coupon by his identifier
	 *
	 * @param   string  $couponIdentifier  the uniq identifier
	 *
	 * @return mixed
	 */
	private function getCouponByIdentifier($couponIdentifier)
	{
		$couponTable = $this->getTable();
		$key = ['identifier' => $couponIdentifier];

		return $couponTable->load($key) ? $couponTable : false;
	}

	/**
	 * Get the ticket fee after discount
	 *
	 * @param   FOFTable  $ticket  the ticket
	 * @param   integer   $fee     the fee
	 *
	 * @return int
	 */
	public function getTicketDiscountedFee($ticket, $fee)
	{
		// Check if we use a coupon
		if ($ticket->processdata['coupon'] != "")
		{
			$coupon = $this->getCouponByIdentifier($ticket->processdata['coupon']);
			$fee = $this->calculateDiscountedFee($coupon, $fee);
		}

		return $fee;
	}
}
