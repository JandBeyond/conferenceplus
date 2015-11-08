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

class ConferenceplusModelDays extends ConferenceplusModelDefault
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
		$this->blacklistFilters('start');

		$query = parent::buildQuery($overrideLimits);

		$db    = $this->getDbo();

		$formName = $this->getState('form_name');

		if ($formName == 'form.default')
		{
			$query->select('e.name AS eventname');

			// Join events
			$query->join('INNER', '#__conferenceplus_events AS e ON e.conferenceplus_event_id = day.event_id');

			$query->where($db->qn('e.enabled') . ' = 1');
		}

		return $query;
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

		// Check time and date
		$result = $this->checkDate('sdate');

		if ($result !== false)
		{
			$data['sdate'] = $result;
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
		}

		// Make sure the dates have the correct format
		foreach (array ('sdate') as $d)
		{
			if (array_key_exists($d, $data) && $data[$d] != '')
			{
				$data[$d] = $this->formatDate($data[$d]);

				if ($data[$d] === false)
				{
					$this->setError('Date format not valid: ' . $d);

					return false;
				}
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

		if ($record->sdate == '0000-00-00')
		{
			$record->sdate = '';
		}

		if (strpos($record->sdate, '-') !== false)
		{
			list($y, $m, $d) = explode('-', $record->sdate);
			$record->sdate = $d . '.' . $m . '.' . $y;
		}

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
