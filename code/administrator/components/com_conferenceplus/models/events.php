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

class ConferenceplusModelEvents extends ConferenceplusModelDefault
{

	use Conferenceplus\Date\Helper;

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

		// Combine params
		$form = $this->getForm();
		$params = array_keys($form->getFieldset('params'));
		$paramsData = array();

		foreach ($params as $param)
		{
			if (array_key_exists($param, $data))
			{
				$paramsData[$param] = $data[$param];
			}
		}

		$data['params'] = json_encode($paramsData);

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

		$this->prepareItemTimeFields($record);

		$this->getEventParams($record);
	}

	/**
	 * Parse params field and return an array of params
	 *
	 * @param   FOFTable  &$table  The table instance we fetched
	 *
	 * @return  array  the params
	 */
	public function getEventParams(&$table)
	{
		$form = $this->getForm(array(), false, 'form.form');
		$params = array_keys($form->getFieldset('params'));
		$paramsData = json_decode($table->params, true);

		foreach ($params as $param)
		{
			if (array_key_exists($param, $paramsData))
			{
				$table->$param = $paramsData[$param];
			}
		}

		return $paramsData;
	}
}
