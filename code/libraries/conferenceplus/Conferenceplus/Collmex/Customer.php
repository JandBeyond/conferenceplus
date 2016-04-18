<?php

namespace Conferenceplus\Collmex;

use Conferenceplus\Collmex\Exception\CreateCustomerException;
use \MarcusJaschen\Collmex\Type\Customer as CollmexCustomer;

/**
 * Class Customer
 * @package  Conferenceplus\Collmex
 * @since   1.0
 */
class Customer extends Base
{
    /**
     * @param $data
     * @return bool
     *
     * @throws CreateCustomerException
     */
    public function create($data)
    {
        $customerData = [];

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
        $customerData['output_medium'] = CollmexCustomer::OUTPUT_MEDIUM_EMAIL;
        $customerData['inactive']      = CollmexCustomer::STATUS_ACTIVE;

        $customerData['country']       = $data['invoicecountry'];

        $customerData['client_id'] = "";

        $customer = new CollmexCustomer($customerData);

        // send HTTP request and get response object
        $respose = $this->request->send($customer->getCsv());

        if ($respose->isError())
        {
            $msg = "Collmex error: " . $respose->getErrorMessage() . "; Code=" . $respose->getErrorCode();

            throw new CreateCustomerException($msg);
        }

        $newObject = $respose->getFirstRecord();

        return $newObject->new_id;
    }
}
