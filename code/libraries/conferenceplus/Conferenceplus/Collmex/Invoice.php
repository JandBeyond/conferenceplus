<?php

namespace Conferenceplus\Collmex;

use Conferenceplus\Collmex\Exception\CreateInvoiceException;

/**
 * Class Invoice
 * @since   1.0
 */
class Invoice extends Base
{
    /**
     * @param $data
     * @return bool
     *
     * @throws CreateInvoiceException
     */
    public function create($data)
    {
        $customerData = array();

        if (! empty($data['invoicecompany']))
        {
            $customerData['firm'] = $data['invoicecompany'];
        }

        if (! empty($data['invoiceline2']))
        {
            $customerData['department']    = $data['invoiceline2'];
        }

        $customerData['forename']      = $data['firstname'];
        $customerData['lastname']      = $data['lastname'];
        $customerData['street']        = $data['invoicestreet'];
        $customerData['zipcode']       = $data['invoicepcode'];
        $customerData['city']          = $data['invoicecity'];
        $customerData['email']         = $data['email'];
        $customerData['output_medium'] = Customer::OUTPUT_MEDIUM_EMAIL;
        $customerData['inactive']      = Customer::STATUS_ACTIVE;

        $customerData['country']       = $data['invoicecountry'];

        $customer = new CollmexCustomer($customerData);

        // send HTTP request and get response object
        $respose = $this->request->send($customer->getCsv());

        if ($respose->isError())
        {
            $msg = "Collmex error: " . $respose->getErrorMessage() . "; Code=" . $respose->getErrorCode();

            throw new CreateInvoiceException($msg);
        }

        $newObject = $respose->getFirstRecord();

        return $newObject->new_id;
    }
}
