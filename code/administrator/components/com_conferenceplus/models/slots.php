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
 * Class ConferenceplusModelSlots
 * @since   1.0
 */
class ConferenceplusModelSlots extends ConferenceplusModelDefault
{

	use Conferenceplus\Date\Helper;

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 * @since   12.2
	 */
	protected function populateState()
	{
		// Load the filters.
		$this->setState('filter.event_id',
			$this->getUserStateFromRequest('filter.slot.event_id', 'eventname', ''));
	}

	/**
	 * Ajust the query
	 *
	 * @param   boolean  $overrideLimits  Are we requested to override the set limits?
	 *
	 * @return  JDatabaseQuery
	 */
	public function buildQuery($overrideLimits = false)
	{
		$query = parent::buildQuery($overrideLimits);

		$db    = $this->getDbo();

		$formName = $this->getState('form_name');

		if ($formName == 'form.default')
		{
			// Join days
			$query->join('INNER', '#__conferenceplus_days AS day ON slot.day_id = day.conferenceplus_day_id')
				->select($db->qn('day.name') . ' AS ' . $db->qn('dayname'));

			// Join events
			$query->join('INNER', '#__conferenceplus_events AS e ON e.conferenceplus_event_id = day.event_id')
				->select('e.name AS eventname')
				->where($db->qn('e.enabled') . ' = 1');

			// Filter
			$filterevent_id = $this->getState('filter.event_id');

			if ( ! empty($filterevent_id))
			{
				$query->where($db->qn('e.conferenceplus_event_id') . ' = ' . $db->q($filterevent_id));
			}

		}

		return $query;
	}


	/**
	 * This method can be overriden to automatically do something with the
	 * list results array. You are supposed to modify the list which was passed
	 * in the parameters; DO NOT return a new array!
	 *
	 * @param   array  &$resultArray  An array of objects, each row representing a record
	 *
	 * @return  void
	 */
	protected function onProcessList(&$resultArray)
	{
		require_once JPATH_SITE . '/libraries/conferenceplus/form/field/slottype.php';

		$slottype = new ConferenceplusFormFieldSlottype;

		foreach($resultArray AS $result)
		{
			$result->slottype = JText::_($slottype->getOption($result->slottype));
			$result->stime = substr($result->stime, 0, 5);
			$result->etime = substr($result->etime, 0, 5);
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

		$dtFields = array('s','e');

		foreach ($dtFields as $dtf)
		{
			// Preset default values
			$data[$dtf . 'time'] = '00:00:00';
			$data[$dtf . 'timeset'] = '0';

			$result = $this->checkTime($dtf . 'time');

			if ($result !== false)
			{
				$data[$dtf . 'time'] = $result;
				$data[$dtf . 'timeset'] = 1;
			}
		}

		return true;
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

		$record->addKnownField('stimehh');
		$record->addKnownField('stimemm');
		$record->addKnownField('etimehh');
		$record->addKnownField('etimemm');

		if ($record->stimeset == 1)
		{
			list($stimehh, $stimemm) = explode(":", $record->stime);
			$record->stimehh = (int) $stimehh;
			$record->stimemm = (int) $stimemm;
		}

		if ($record->etimeset == 1)
		{
			list($etimehh, $etimemm) = explode(":", $record->etime);
			$record->etimehh = (int) $etimehh;
			$record->etimemm = (int) $etimemm;
		}
	}
}
