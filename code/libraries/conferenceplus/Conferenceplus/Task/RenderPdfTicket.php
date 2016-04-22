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

use Conferenceplus\Composer\Number as Number;

/**
 * Class RenderPdfTicket
 *
 * @package  Conferenceplus\Task
 * @since    0.0.1
 */
class RenderPdfTicket extends RenderPdf
{
	/**
	 * Fire up the next needed task if there are any
	 *
	 * @param   \JTable  $task  taskdata
	 *
	 * @return bool
	 */
	protected function instantiateNextTasks($task)
	{
		$next = new SendTicket($this->config);

		return $next->create($task->processdata);
	}

	/**
	 * prepare the data from processdata
	 *
	 * @param   mixed  $task  task data
	 *
	 * @return array
	 */
	protected function prepareData($task)
	{
		$ticket     = $task->processdata['processdata']['ticket']['ticket'];
		$tickettype = $task->processdata['processdata']['ticket']['tickettype'];

		// That's needed to find the correct table
		$config['input']['option'] = 'com_conferenceplus';
		$eventTable = \FOFTable::getAnInstance('event', 'JTable', $config);

		if ( ! $eventTable->load($tickettype['event_id']))
		{
			return false;
		}

		$name    = $eventTable->name;

		$data['name'] = $ticket['firstname'] . ' ' . $ticket['lastname'];

		$data['ticket_number'] = $name . '-' . str_pad($ticket['conferenceplus_ticket_id'], 8, '0', STR_PAD_LEFT);
		$data['ticket_type']   = $tickettype['name'];
		$data['food']          = \JText::_($ticket['processdata']['ask4food']);
		$data['tshirtsize']    = $ticket['processdata']['ask4tshirtsize'];
		$data['basename'] 	   = $data['ticket_number'];
		$data['email']		   = $ticket['email'];

		return $data;
	}
}
