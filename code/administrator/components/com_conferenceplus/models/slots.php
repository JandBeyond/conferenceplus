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
			$query->join('INNER', '#__conferenceplus_days AS d ON slot.day_id = d.conferenceplus_day_id')
				->select($db->qn('d.name') . ' AS ' . $db->qn('dayname'));
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
