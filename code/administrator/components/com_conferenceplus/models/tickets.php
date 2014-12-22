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

class ConferenceplusModelTickets extends ConferenceplusModelDefault
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

		$tickettype = FOFModel::getAnInstance('tickettypes', 'ConferenceplusModel');
		$tickettypeTable = $tickettype->getTable();
		$tickettypeTable->load(JFactory::getApplication()->getUserState('com_conferenceplus.ticketId'));
		$event_id = $tickettypeTable->event_id;

		$event = FOFModel::getAnInstance('events', 'ConferenceplusModel');
		$event->setId($event_id);
		$eventTable = $event->getTable();
		$eventTable->load($event_id);
		$params = $event->getEventParams($eventTable);

		$record->eventParams = $params;
	}

	/**
	 * Allows data and form manipulation after preprocessing the form
	 *
	 * @param   FOFForm  &$form  A FOFForm object.
	 * @param   array    &$data  The data expected for the form.
	 *
	 * @codeCoverageIgnore
	 *
	 * @return  void
	 */
	public function onAfterPreprocessForm(FOFForm &$form, &$data)
	{
		$a = 1;
	}

}
