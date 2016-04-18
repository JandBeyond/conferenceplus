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

use Conferenceplus\Collmex\Invoice;


/**
 * Class CreateInvoiceCollmex
 *
 * @package  Conferenceplus\Task
 * @since    1.0.0
 */
class CreateInvoiceCollmex extends BaseTask
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
		$invoice = new Invoice($this);

		$data = $this->prepareDataArray($task->processdata);

		try
		{
			$newInvoiceId = $invoice->create($data);
		}
		catch(Exception $e)
		{
			return false;
		}

		$task->processdata['invoice_id']  = $newInvoiceId;

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
		$next = new CreateInvoiceCollmex($this->config);

		return $next->create($task->processdata);
	}

	private function prepareDataArray($processdata)
	{
		$data = [];

		$customerId = $processdata['customer_id'];

		/*
		$formdata   = $ticketdata['processdata'];

		$data['customer_id']    = $customerId;
		$data['invoicepcode']   = $formdata['invoicepcode'];
		$data['invoicecity']    = $formdata['invoicecity'];
		$data['invoicecompany'] = $formdata['invoicecompany'];
		$data['invoiceline2']   = $formdata['invoiceline2'];
		$data['firstname']      = $ticketdata['firstname'];
		$data['lastname']       = $ticketdata['lastname'];
		$data['email']          = $ticketdata['email'];
		*/

		return $data;
	}
}
