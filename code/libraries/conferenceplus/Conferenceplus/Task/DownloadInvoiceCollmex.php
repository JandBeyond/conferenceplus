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
class DownloadInvoiceCollmex extends BaseTask
{

	/**
	 * run before the task is executed
	 *
	 * @param   mixed  &$task  task data
	 *
	 * @return bool
	 */
	public function onBeforeDoProcess(&$task)
	{
		// We need to make sure that we have addressdata
		if ( ! array_key_exists('invoiceaddress', $task->processdata))
		{
			$ticket = $task->processdata['processdata']['ticket']['ticket'];

			if (isset($ticket['processdata']['invoiceaddress']))
			{
				$task->processdata['invoiceaddress'] = $ticket['processdata']['invoiceaddress'];
			}
		}

		return true;
	}

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
		// for INTERNAL Tickets it isn't needed
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
		if ($this->config->get('') == 1)
		{
			$next = new SendInvoiceCollmex($this->config);

			return $next->create($task->processdata);
		}


		return $next->create($task->processdata);
	}

}
