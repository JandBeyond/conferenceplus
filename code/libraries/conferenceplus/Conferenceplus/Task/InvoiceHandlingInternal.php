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
 * Class InvoiceHandlingInternal
 *
 * @package  Conferenceplus\Task
 * @since    0.0.1
 */
class InvoiceHandlingInternal extends BaseTask
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
		$tickettype = $task->processdata['processdata']['ticket']['tickettype'];

		$result = $this->createInvoice($tickettype['event_id'], $task);

		if ($result === false)
		{
			return false;
		}

		$task->processdata['invoice_id']   = $result['id'];
		$task->processdata['invoice_hash'] = $result['hash'];

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
		$next = new RenderPdfInvoice($this->config);

		return $next->create($task->processdata);
	}

	/**
	 * Create a row in the invoice table
	 *
	 * @param   integer  $eventId  the event id
	 * @param   mixed    $task     task
	 *
	 * @return bool
	 */
	private function createInvoice($eventId, $task)
	{
		// That's needed to find the correct table
		$config['input']['option'] = 'com_conferenceplus';

		$invoiceTable = \FOFTable::getAnInstance('invoice', 'JTable', $config);
		$payment_id   = $task->processdata['payment_id'];

		// Try to load if we have an existing invoice
		$key = ['payment_id' => $payment_id];
		$isNew = ! $invoiceTable->load($key);

		if ($isNew)
		{
			$iNo = $this->getInvoiceNumber($eventId);
		}

		$invoiceTable->payment_id = $task->processdata['payment_id'];
		$invoiceTable->data       = json_encode($task->processdata);

		if ($isNew)
		{
			$invoiceTable->created    = $this->date->getInstance()->toSql();
			$invoiceTable->identifier = $iNo;
		}
		else
		{
			$invoiceTable->modified = $this->date->getInstance()->toSql();
		}

		$invoiceTable->hash       = base_convert(time(), 10, 32);
		$invoiceTable->address    = $task->processdata['invoiceaddress'];

		if (! $invoiceTable->store())
		{
			return false;
		}

		// We need to pause a bit so that the hash is uniq
		sleep(1);

		return ['id' => $invoiceTable->conferenceplus_invoice_id, 'hash' => $invoiceTable->hash];
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
