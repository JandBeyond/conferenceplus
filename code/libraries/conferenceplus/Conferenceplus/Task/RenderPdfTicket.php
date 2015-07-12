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
 * Class RenderPdfInvoice
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
		$next = new SendInvoice($this->config);

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
		if ( ! array_key_exists('invoice_id', $task->processdata))
		{
			return false;
		}

		$invoice_id = $task->processdata['invoice_id'];

		// That's needed to find the correct table
		$config['input']['option'] = 'com_conferenceplus';

		$invoiceTable = \FOFTable::getAnInstance('invoice', 'JTable', $config);
		$loadResult = $invoiceTable->load($invoice_id);

		if ( ! $loadResult)
		{
			return false;
		}

		$processdata = json_decode($invoiceTable->data, true);

		$invoiceTable->data = $processdata;

		$processkey = $processdata['processkey'];
		$ticket 	= $processdata['processdata']['ticket']['ticket'];
		$tickettype = $processdata['processdata']['ticket']['tickettype'];
		$ipn 		= $processdata['processdata']['ipn'];

		$eventTable = \FOFTable::getAnInstance('event', 'JTable', $config);
		$loadResult = $eventTable->load($tickettype['event_id']);

		if ( ! $loadResult)
		{
			return false;
		}

		$data['invoice_number'] = $invoiceTable->identifier;
		$data['addressblock']   = nl2br($invoiceTable->address);

		$date = $this->date->getInstance($ticket['created']);

		$data['date']           = $date->format('d. F Y');

		$data['productname'] = $tickettype['name'];

		// Needed because first version hadn't a productname can be removed after JAB15
		if (isset($tickettype['productname']))
		{
			$data['productname']    = $tickettype['productname'];
		}

		$data['productdesc']    = $tickettype['description'];
		$data['totalfee'] 		= $tickettype['fee'];
		$data['productfee'] 	= $tickettype['fee'];
		$data['tax'] 			= \JText::sprintf('COM_CONFERENCEPLUS_INVOICEVAT', '0%' );
		$data['taxfee'] 		= '0';
		$vat 					= $tickettype['vat'];

		$numberComposer = new Number(['params' => $this->config['cpconf']]);

		if ($vat != 0)
		{
			// Needs calculation
			$data['productfee'] = $data['totalfee'] * 100 / (100 + $vat);
			$data['taxfee']		= $numberComposer->money(($data['totalfee'] - $data['productfee']) / 100);
			$data['tax'] 		= \JText::sprintf('COM_CONFERENCEPLUS_INVOICEVAT', $vat . '%' );
		}

		$data['productfee'] = $numberComposer->money($data['productfee'] / 100);
		$data['totalfee'] 	= $numberComposer->money($data['totalfee'] / 100);

		$data['note'] 		= \JText::sprintf('COM_CONFERENCEPLUS_INVOICENOTE', $processkey);

		$data['basename'] 	= $invoiceTable->hash;
		$data['email'] 	    = $ticket['email'];

		$task->processdata['invoice_identifier'] = $invoiceTable->identifier;

		return $data;
	}
}
