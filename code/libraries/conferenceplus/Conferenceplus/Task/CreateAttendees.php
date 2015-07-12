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

namespace Conferenceplus\Task;

/**
 * Class CreateAttendees
 *
 * @package  Conferenceplus\Task
 * @since    0.0.1
 */
class CreateAttendees extends BaseTask
{
	/**
	 * Do the work
	 *
	 * @param   Repository  $task  task data
	 *
	 * @return bool
	 */
	protected function doProcess($task)
	{
		if ( ! $this->createTicketAttendees($task))
		{
			return false;
		}

		return $this->instantiateNextTasks($task);
	}

	/**
	 * Create Attendees for a ticket
	 *
	 * @param   mixed  $task  task
	 *
	 * @return bool
	 */
	private function createTicketAttendees($task)
	{
		$ticket = $task->processdata['processdata']['ticket']['ticket'];

		$data['ticket_id']  = $ticket['conferenceplus_ticket_id'];
		$data['firstname']  = $ticket['firstname'];
		$data['lastname']   = $ticket['lastname'];
		$data['email']      = $ticket['email'];

		$fields = ['gender', 'tshirtsize', 'food', 'food0'];

		foreach ($fields AS $field)
		{
			$askfield     = 'ask4' . $field;
			$data[$field] = isset($ticket['processdata'][$askfield]) ? $ticket['processdata'][$askfield] : '';
		}

		$data['partner']    = 0;
		$data['created']    = $this->date->getInstance()->toSql();

		if ( ! $this->createAttendee($data))
		{
			return false;
		}

		$tickettype = $task->processdata['processdata']['ticket']['tickettype'];

		if ($tickettype['partnerticket'] == 0)
		{
			return true;
		}

		$data['food']       = $data['food0'];
		$data['partner']    = 1;
		$data['gender']     = '';

		return $this->createAttendee($data);
	}

	/**
	 * Create a row in the attendee table
	 *
	 * @param   array  $data  the data for the attendee
	 *
	 * @return bool
	 */
	private function createAttendee($data)
	{
		// That's needed to find the correct table
		$config['input']['option'] = 'com_conferenceplus';

		$attendeeTable = \FOFTable::getAnInstance('attendee', 'JTable', $config);

		$attendeeTable->ticket_id  = $data['ticket_id'];
		$attendeeTable->firstname  = $data['firstname'];
		$attendeeTable->lastname   = $data['lastname'];
		$attendeeTable->email      = $data['email'];
		$attendeeTable->partner    = $data['partner'];
		$attendeeTable->gender     = $data['gender'];
		$attendeeTable->food       = $data['food'];
		$attendeeTable->tshirtsize = $data['tshirtsize'];
		$attendeeTable->created    = $data['created'];

		return $attendeeTable->store();
	}

	/**
	 * Fire up the next needed task if there are any
	 *
	 * @param   \JTable  $task  taskdata
	 *
	 * @return bool
	 */
	protected function instantiateNextTasks($task)
	{
		$next = new RenderPdfTicket($this->config);

		return $next->create($task->processdata);
	}
}
