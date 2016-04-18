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

use Conferenceplus\Collmex\Customer;
use Conferenceplus\Collmex\Exception\CreateCustomerException;
use Conferenceplus\Country\Helper as CountryHelper;

/**
 * Class CreateCustomerCollmex
 *
 * @package  Conferenceplus\Task
 * @since    1.0.0
 */
class CreateCustomerCollmex extends BaseTask
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
		$customer = new Customer($this->config);

		$data = $this->prepareDataArray($task->processdata);

		try
		{
			$newCustomerId = $customer->create($data);
		}
		catch(CreateCustomerException $e)
		{
			return false;
		}

		$task->processdata['customer_id']  = $newCustomerId;

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

	/**
	 * @param $processdata
	 *
	 * @return array
     */
	private function prepareDataArray($processdata)
	{
		$data = [];

		$ticketdata = $processdata['processdata']['ticket']['ticket'];
		$formdata   = $ticketdata['processdata'];

		$data['invoicestreet']  = $formdata['invoicestreet'];
		$data['invoicepcode']   = $formdata['invoicepcode'];
		$data['invoicecity']    = $formdata['invoicecity'];
		$data['invoicecompany'] = $formdata['invoicecompany'];
		$data['invoiceline2']   = $formdata['invoiceline2'];
		$data['firstname']      = $ticketdata['firstname'];
		$data['lastname']       = $ticketdata['lastname'];
		$data['email']          = $ticketdata['email'];

		$countryHelper           = new CountryHelper;
		$data['invoicecountry']  = $countryHelper->numericToAlpha2Code($formdata['invoicecountry']);

		return $data;
	}
}
