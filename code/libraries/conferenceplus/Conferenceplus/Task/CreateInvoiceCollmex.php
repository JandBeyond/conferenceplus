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
		$invoice = new Invoice($this->config);

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

		return true;
	}

	/**
	 * Prepare the data
	 *
	 * @param  array  $processdata
	 *
	 * @return array|bool
     */
	private function prepareDataArray($processdata)
	{
		$data = [];

		$customerId = $processdata['customer_id'];
		$processId  = $processdata['processkey'];
		$tickettype = $processdata['processdata']['ticket']['tickettype'];

		// That's needed to find the correct table
		$config['input']['option'] = 'com_conferenceplus';
		$eventTable = \FOFTable::getAnInstance('event', 'JTable', $config);

		if ( ! $eventTable->load($tickettype['event_id']))
		{
			return false;
		}

		$name    = $eventTable->name;
		$city    = $eventTable->city;
		$country = $eventTable->country;
		$product = $tickettype['productname'] . ' -  ' . $name . ', ' . $city . ', ' . $country;

		// must exist
		$data['customer_id'] = $customerId;

		// Payment reference
		$data['processid'] = $processId;

		$data['product_description'] = $product;
		$data['quantity']            = 1;
		$data['price']               = $tickettype['fee'];
		$data['tax_rate']			 = $tickettype['vat'];
		$data['eventname']			 = $name;

		return $data;
	}
}
