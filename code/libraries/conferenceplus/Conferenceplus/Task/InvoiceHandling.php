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
		// for INTERNAL Tickets it isn't needed
		if ($task->processdata['processdata']['paymentprovider'] == 'INTERNAL')
		{
			// DONE
			return true;
		}

		$tickettype = $task->processdata['processdata']['ticket']['tickettype'];

		$iNo = $this->getInvoiceNumber($tickettype['event_id']);

		if ( ! $this->createInvoice($iNo, $task))
		{
			return false;
		}

		$rp = new RenderPdf($this->config);

		return $rp->create($task->processdata);
	}

	/**
	 * Create a row in the invoice table
	 *
	 * @param   string  $iNo   invoice number
	 * @param   mixed   $task  task
	 *
	 * @return bool
	 */
	private function createInvoice($iNo, $task)
	{
		// That's needed to find the correct table
		$config['input']['option'] = 'com_conferenceplus';

		$invoiceTable = \FOFTable::getAnInstance('invoice', 'JTable', $config);

		$invoiceTable->payment_id = $task->processdata['payment_id'];
		$invoiceTable->identifier = $iNo;
		$invoiceTable->data       = json_encode($task->processdata);
		$invoiceTable->created    = $this->date->getInstance()->toSql();
		$invoiceTable->hash       = base_convert(time(), 10, 32);

		$ticket = $task->processdata['processdata']['ticket']['ticket'];

		$invoiceTable->address    = $ticket['firstname'] . ' ' . $ticket['lastname'];

		return $invoiceTable->store();
	}

	/**
	 * Build the invoice number
	 *
	 * @param   integer  $eventId  the event id
	 *
	 * @return bool|string
	 */
	private function getInvoiceNumber($eventId)
	{
		// That's needed to find the correct table
		$config['input']['option'] = 'com_conferenceplus';

		$eventTable = \FOFTable::getAnInstance('event', 'JTable', $config);

		if ( ! $eventTable->load($eventId))
		{
			return false;
		}

		$name = $eventTable->name;
		$seq  = $this->getNextSeqNumber();

		return $name . '-' . str_pad($seq, 8, '0', STR_PAD_LEFT);
	}

	/**
	 * get the next sequence number
	 *
	 * @return mixed
	 */
	private function getNextSeqNumber()
	{
		$this->db->setQuery('UPDATE #__conferenceplus_invoice_sequence SET id=LAST_INSERT_ID(id+1)');
		$this->db->execute();
		$this->db->setQuery('SELECT LAST_INSERT_ID()');

		return $this->db->loadResult();
	}
}
