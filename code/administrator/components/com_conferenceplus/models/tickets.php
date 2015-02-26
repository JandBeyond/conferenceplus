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
			// Join payments
			$query->join('LEFT', '#__conferenceplus_payments AS p ON p.conferenceplus_payment_id = payment_id')
				->select($db->qn('p.state') . ' AS ' . $db->qn('paymentstate'));
			// Join tickettype
			$query->join('INNER', '#__conferenceplus_tickettypes AS t ON t.conferenceplus_tickettype_id = tickettype_id')
				->select($db->qn('t.productname') . ' AS ' . $db->qn('ticketname'))
				->select($db->qn('t.partnerticket') . ' AS ' . $db->qn('partnerticket'));
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

		$fields = ['ask4gender', 'ask4tshirtsize', 'ask4food', 'ask4food0'];

		$processdata = [];

		foreach ($fields as $field)
		{
			$processdata[$field] = array_key_exists($field, $data) ? $data[$field] : '';
		}

		$data['processdata'] = json_encode($processdata);

		$data['tickettype_id'] = $this->getTicketTypeId();

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

		$tickettypeId = $this->getTicketTypeId();

		if (empty($tickettypeId))
		{
			return;
		}

		$tickettype = FOFModel::getAnInstance('tickettypes', 'ConferenceplusModel');
		$tickettypeTable = $tickettype->getTable();
		$tickettypeTable->load($tickettypeId);
		$event_id = $tickettypeTable->event_id;

		$record->ticketType = $tickettypeTable;

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

		if ('add' == $task)
		{
			$item = $this->record;

			$ticketType = $item->ticketType;

			$eventParams = $item->eventParams;

			$fields = ['ask4gender', 'ask4tshirtsize', 'ask4food'];

			foreach ($fields as $fieldname)
			{
				if ( ! empty($eventParams[$fieldname]))
				{
					$field = $this->createAskFormField($fieldname, $eventParams[$fieldname]);

					$form->setField($field);

					if ($ticketType->partnerticket == 1 && $fieldname == 'ask4food')
					{
						$field = $this->createAskFormField('ask4food', $eventParams['ask4food'], true);

						$form->setField($field);
					}
				}
			}
		}
	}

	/**
	 * create a form field for asking the buyer some questions
	 *
	 * @param  string  $fieldname   the Fieldname
	 * @param  string  $data        the options
	 * @param  bool    $additional  if set to true add a 0 to the fieldname, needed when a question is asked twice
	 *
	 * @return SimpleXMLElement
	 */
	protected function createAskFormField($fieldname, $data, $additional=false)
	{
		$targetfieldname = $additional ? $fieldname . '0' : $fieldname;

		$field = new SimpleXMLElement('<field></field>');
		$field->addAttribute('name', $targetfieldname);
		$field->addAttribute('type', 'list');
		$field->addAttribute('label', 'COM_CONFERENCEPLUS_'. strtoupper($fieldname));
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

	private function getTicketTypeId()
	{
		return JFactory::getApplication()->getUserState('com_conferenceplus.tickettypeId');
	}

}
