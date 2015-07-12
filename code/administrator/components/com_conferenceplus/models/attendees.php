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

class ConferenceplusModelAttendees extends ConferenceplusModelDefault
{
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
			// Join category
			$query->join('INNER', '#__conferenceplus_tickets AS t ON t.conferenceplus_ticket_id = attendee.ticket_id')
				->join('INNER', '#__conferenceplus_tickettypes AS tt ON tt.conferenceplus_tickettype_id = t.tickettype_id')
				->select($db->qn('tt.productname'));
		}

		return $query;
	}

	/**
	 * Adding more useful fields to the resultArray
	 *
	 * @param   array  &$resultArray  An array of objects, each row representing a record
	 *
	 * @return  void
	 */
	protected function onProcessList(&$resultArray)
	{
		$limitstart = $this->input->get('limitstart', 0) == 0 ? 1 : $this->input->get('limitstart') + 1;

		if (FOFPlatform::getInstance()->isBackend())
		{
			foreach ($resultArray AS $key => &$result)
			{
				// Translate fields
				$result->gender = JText::_($result->gender);
				$result->food   = JText::_($result->food);
				$result->num    = $limitstart + $key;
			}
		}
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

		$ticket = FOFTable::getAnInstance('ticket');
		$ticket->load($record->ticket_id);

		$tickettype = FOFTable::getAnInstance('tickettype');
		$tickettype->load($ticket->tickettype_id);
		$event_id = $tickettype->event_id;

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
		$task = $this->input->get('task');

		if ('edit' == $task)
		{
			$item = $this->record;

			$eventParams = $item->eventParams;

			$fields = ['gender', 'tshirtsize', 'food'];

			foreach ($fields as $fieldname)
			{
				if ( ! empty($eventParams['ask4' . $fieldname]))
				{
					$field = $this->createAskFormField($fieldname, $eventParams['ask4' . $fieldname]);

					$form->setField($field);
				}
			}
		}
	}

	/**
	 * create a form field for asking the buyer some questions
	 *
	 * @param   string  $fieldname   the Fieldname
	 * @param   string  $data        the options
	 * @param   bool    $additional  if set to true add a 0 to the fieldname, needed when a question is asked twice
	 *
	 * @return SimpleXMLElement
	 */
	protected function createAskFormField($fieldname, $data, $additional=false)
	{
		$targetfieldname = $additional ? $fieldname . '0' : $fieldname;

		$field = new SimpleXMLElement('<field></field>');
		$field->addAttribute('name', $targetfieldname);
		$field->addAttribute('type', 'list');
		$field->addAttribute('label', 'COM_CONFERENCEPLUS_ASK4' . strtoupper($fieldname));
		$field->addAttribute('required', 'true');
		$field->addAttribute('class', 'inputbox form-control');
		$field->addAttribute('labelclass', 'control-label');
		$field->addAttribute('size', '1');

		$options = explode('|', $data);

		foreach ($options as $value)
		{
			$text   = JText::_($value);
			$option = $field->addChild('option', $text);
			$option->addAttribute('value', $value);
		}

		return $field;
	}
}
