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
 * Class InvoiceHandling
 *
 * @package  Conferenceplus\Task
 * @since    0.0.1
 */
class InvoiceHandling extends BaseTask
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
		// 1st check is if we need to send an invoice at all
		// for freetickets == INTERNAL Tickets it isn't needed
		if ($task->processdata['processdata']['paymentprovider']['processkey'] == 'INTERNAL')
		{
			// DONE
			return true;
		}

		return $this->instantiateNextTasks($task);
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
		if ($this->config['cpconf']->get('invoicehandeling') == 1)
		{
			$next = new InvoiceHandlingCollmex($this->config);

			return $next->create($task->processdata);
		}

		$next = new InvoiceHandlingConferenceplus($this->config);

		return $next->create($task->processdata);
	}

}
