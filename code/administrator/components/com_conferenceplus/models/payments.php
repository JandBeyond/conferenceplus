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
 * Class ConferenceplusModelPayments
 *
 * @since  0.0.1
 */
class ConferenceplusModelPayments extends ConferenceplusModelDefault
{

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

		$ticketId = $this->getTicketId();

		if (empty($ticketId))
		{
			return;
		}

		$ticketData       = $this->getTicketData($ticketId);
		$paymentProviders = [];
		$freeTicket       = $ticketData->tickettype->fee == 0;

		if ( ! $freeTicket)
		{
			$paymentProviders = $this->getPaymentProviders($ticketData);
		}

		$record->ticketData       = $ticketData;
		$record->freeTicket       = $freeTicket;
		$record->paymentProviders = $paymentProviders;
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

		$processData = json_decode($table->processdata, true);
		$ticketId = $processData['ticket']['ticket']['conferenceplus_ticket_id'];

		$ticketTable = FOFTable::getAnInstance('tickets');
		$ticketTable->load($ticketId);

		$ticketTable->payment_id = $table->conferenceplus_payment_id;
		$ticketTable->modified   = JFactory::getDate()->toSql();

		$ticketTableResult = $ticketTable->store();

		$taskCreateResult = true;

		if ($this->_isNewRecord)
		{
			$task = new Conferenceplus\Task\AfterPayment;

			$taskCreateResult = $task->create($table);
		}

		// Check if we use a coupon
		$data = json_decode($ticketTable->processdata, true);

		if ($data['coupon'] != "")
		{
			$coupon = FOFModel::getAnInstance('coupons', 'ConferenceplusModel');
			$coupon->setUsed($ticketId, $table->conferenceplus_payment_id);
		}

		return ($ticketTableResult && $taskCreateResult);
	}

	/**
	 * get Payment providers
	 *
	 * @param   mixed  $ticketData  the ticketdata
	 *
	 * @return array
	 */
	private function getPaymentProviders($ticketData)
	{
		$params = $this->prepareParams($ticketData);

		$dispatcher = JEventDispatcher::getInstance();

		JPluginHelper::importPlugin('payment');

		$results = $dispatcher->trigger('onPaymentGetForm', array($params));
		$rvalue  = [];

		foreach ($results as $result)
		{
			if (!empty($result))
			{
				$rvalue[] = $result;
			}
		}

		return $rvalue;
	}

	/**
	 * runs the callback from a payment provider
	 *
	 * @param   string  $paymentMethod  the paymentmethod
	 *
	 * @return bool
	 */
	public function runCallback($paymentMethod)
	{
		$POST = new FOFInput('POST');
		$rawDataPost = $POST->getArray();

		$GET = new FOFInput('GET');
		$rawDataGet = $GET->getArray();

		$data = array_merge($rawDataGet, $rawDataPost);

		Conferenceplus\Helper::logData($data, 'DEBUG');

		// Some plugins result in an empty Itemid being added to the request
		// data, screwing up the payment callback validation in some cases (e.g.PayPal).
		if (array_key_exists('Itemid', $data))
		{
			if (empty($data['Itemid']))
			{
				unset($data['Itemid']);
			}
		}

		$ticketId = array_key_exists('custom', $data) ? (int) $data['custom'] : - 1;

		if ($ticketId < 1)
		{
			return false;
		}

		$ticketData = $this->getTicketData($ticketId);
		$params     = $this->prepareParams($ticketData);

		$dispatcher = JEventDispatcher::getInstance();

		JPluginHelper::importPlugin('payment');

		$results = $dispatcher->trigger('onPaymentCallback', array($paymentMethod, $data, $params));

		foreach ($results as $result)
		{
			if ($result !== false)
			{
				$pluginData = json_decode($result, true);

				// Save payment
				$saveData = [];
				$saveData['processkey']  = $pluginData['processkey'];
				$saveData['state']       = $pluginData['state'];
				$saveData['processdata'] = $this->prepareProcessData($pluginData, $ticketData, $data);
				$saveData['name']        = $ticketData->ticket->firstname . ' ' .
											$ticketData->ticket->lastname . ', ' .
											$ticketData->ticket->email;

				Conferenceplus\Helper::logData($saveData, 'DEBUG');

				$result = parent::save($saveData);

				$this->deleteTicketId();

				Conferenceplus\Helper::logData($result ? 'SAVE SUCCESS' : 'SAVE FAIL', 'DEBUG');

				return $result;
			}
		}

		return false;
	}

	/**
	 * merge data and json decode
	 *
	 * @param   array  $pluginData  result data from the plugin
	 * @param   mixed  $ticketData  ticketdata
	 * @param   array  $ppvData     data from payment provider
	 *
	 * @return mixed|string
	 */
	public function prepareProcessData($pluginData, $ticketData, $ppvData)
	{
		$mergedData = [];
		$mergedData['paymentprovider'] = $pluginData;
		$mergedData['ticket']          = $ticketData;
		$mergedData['ipn']             = array_map('utf8_encode', $ppvData);

		return json_encode($mergedData);
	}

	/**
	 * get a ticket id from the session
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	private function getTicketId()
	{
		return JFactory::getApplication()->getUserState('com_conferenceplus.ticketId', null);
	}

	/**
	 * delete ticket id save in session
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	private function deleteTicketId()
	{
		return JFactory::getApplication()->setUserState('com_conferenceplus.ticketId', null);
	}

	/**
	 * get ticketdata based on a ticketId
	 *
	 * @param   integer  $ticketId  id of the ticket
	 *
	 * @return stdClass
	 */
	public function getTicketData($ticketId = 0)
	{
		$ticketTable = FOFTable::getAnInstance('tickets');
		$ticketTable->load($ticketId);
		$tickettypeId = $ticketTable->tickettype_id;

		$tickettypeTable = FOFTable::getAnInstance('tickettypes');
		$tickettypeTable->load($tickettypeId);

		// Check if we use a coupon
		$data = json_decode($ticketTable->processdata, true);

		if ($data['coupon'] != "")
		{
			$coupon = FOFModel::getAnInstance('coupons', 'ConferenceplusModel');
			$tickettypeTable->fee = $coupon->getTicketDiscountedFee($ticketTable, $tickettypeTable->fee);
		}

		$ticketData             = new stdClass;
		$ticketData->ticket     = $ticketTable;
		$ticketData->tickettype = $tickettypeTable;

		return $ticketData;
	}

	/**
	 * prepare params array set with ticket data
	 *
	 * @param   mixed  $ticketData  the ticketdata
	 *
	 * @return array
	 */
	private function prepareParams($ticketData)
	{
		$params = [];

		$params['net_amount']  = $ticketData->tickettype->fee / 100;
		$params['item_name']   = $ticketData->tickettype->productname;
		$params['item_number'] = 1;
		$params['currency']    = 'EUR';
		$params['custom']      = $ticketData->ticket->conferenceplus_ticket_id;
		$params['firstname']   = $ticketData->ticket->firstname;
		$params['lastname']    = $ticketData->ticket->lastname;
		$params['email']       = $ticketData->ticket->email;

		$Itemid  = Conferenceplus\Route\Helper::getItemid('');
		$success = JUri::root()
					. 'index.php?option=com_conferenceplus&view=payment&layout=confirm&t='
					. $ticketData->ticket->conferenceplus_ticket_id
					. '&Itemid=' . $Itemid;

		$params['success'] = $success;

		$cancel = JUri::root()
			. 'index.php?option=com_conferenceplus&view=payment&layout=cancel&t='
			. $ticketData->ticket->conferenceplus_ticket_id
			. '&Itemid=' . $Itemid;

		$params['cancel'] = $cancel;

		return $params;
	}
}
