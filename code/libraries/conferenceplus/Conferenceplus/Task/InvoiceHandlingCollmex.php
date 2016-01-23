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

use \MarcusJaschen\Collmex\Client\Curl as CurlClient;
use \MarcusJaschen\Collmex\Request;
use \MarcusJaschen\Collmex\Type\Customer;
use \MarcusJaschen\Collmex\Type\Invoice;


/**
 * Class InvoiceHandlingCollmex
 *
 * @package  Conferenceplus\Task
 * @since    0.0.2
 */
class InvoiceHandlingCollmex extends BaseTask
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
		$resultExternal = $this->createCollmexInvoice($task);

		if ($resultExternal === false)
		{
			return false;
		}

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
		$next = new DownloadInvoiceCollmex($this->config);

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
	
	private function createCollmexInvoice()
	{
		$user     = $this->config->get('collmexuser');
		$pass     = $this->config->get('collmexpass');
		$clientId = $this->config->get('collmexclientnumber');

		// initialize HTTP client
		$collmexClient = new CurlClient($user, $pass, $clientId);

		// create request object
		$collmexRequest = new Request($collmexClient);

		$invoice = new Invoice(
			array(
				'invoice_type'                   => Invoice::INVOICE_TYPE_INVOICE,
				'client_id'                      => null,
				'order_id'                       => null,
				'customer_id'                    => 9999,
				'customer_forename'              => null,
				'customer_lastname'              => null,
				'customer_firm'                  => null,
				'customer_department'            => null,
				'customer_street'                => null,
				'customer_zipcode'               => null,
				'customer_city'                  => null,
				'customer_country'               => null,
				'customer_phone'                 => null,
				'customer_phone_2'               => null,
				'customer_fax'                   => null,
				'customer_email'                 => null,
				'customer_bank_account'          => null,
				'customer_bank_code'             => null,
				'customer_bank_account_owner'    => null,
				'customer_bank_iban'             => null,
				'customer_bank_bic'              => null,
				'customer_bank_name'             => null,
				'customer_vat_id'                => null,
				'reserved'                       => null,
				'invoice_date'                   => null,
				'price_date'                     => null,
				'terms_of_payment'               => null,
				'currency'                       => null,
				'price_group'                    => null,
				'discount_id'                    => null,
				'discount_final'                 => null,
				'discount_reason'                => null,
				'invoice_text'                   => null,
				'final_text'                     => null,
				'annotation'                     => null,
				'deleted'                        => null,
				'language'                       => null,
				'employee_id'                    => null,
				'agent_id'                       => null,
				'system_name'                    => null,
				'status'                         => null,
				'discount_final_2'               => null,
				'discount_final_2_reason'        => null,
				'shipping_id'                    => null,
				'shipping_costs'                 => null,
				'cod_costs'                      => null,
				'delivery_email'                 => null,
				'position_type'                  => null,
				'product_id'                     => null,
				'product_description'            => null,
				'quantity_unit'                  => null,
				'quantity'                       => null,
				'price'                          => null,
				'price_quantity'                 => null,
				'position_discount'              => null,
				'position_value'                 => null,
				'product_type'                   => null,
				'tax_rate'                       => null,
				'foreign_tax'                    => null,
				'customer_order_position'        => null,
				'revenue_type'                   => null,
				'sum_over_positions'             => null,
				'revenue'                        => null,
				'costs'                          => null,
				'gross_profit'                   => null,
				'margin'                         => null,
			)
		);

		// send HTTP request and get response object
		$collmexResponse = $collmexRequest->send($invoice->getCsv());

		if ($collmexResponse->isError())
		{
			return false;
		}

		return 	$collmexResponse;
	}	

}
