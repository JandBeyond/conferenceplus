<?php

namespace Conferenceplus\Collmex;

use Conferenceplus\Collmex\Exception\CreateInvoiceException;
use \MarcusJaschen\Collmex\Type\Invoice as CollmexInvoice;
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
        $customerData = [];

        $customerData['customer_id'] = $data['customer_id'];
        $customerData['invoice_type'] = CollmexInvoice::INVOICE_TYPE_INVOICE;
        $customerData['annotation'] = $data['processid'];
        $customerData['invoice_text'] = $data['eventname'];

        // Zahlungsbedingungen paypal == 14
        $customerData['terms_of_payment'] = "14";
        $customerData['currency'] = 'EUR';
        $customerData['product_description'] = $data['product_description'];
        $customerData['quantity_unit'] = "PCE";
        $customerData['quantity'] = "1";
        $customerData['price_quantity'] = "PCE";
        $customerData['price'] = str_replace('.', ',', (string)($data['price'] / 100));
        $customerData['product_type'] = "1";

        // Calculate Tax rate
        switch ($data['tax_rate'])
        {
            case '0':
                $customerData['tax_rate'] = CollmexInvoice::TAX_RATE_TAXFREE;
                break;
            case '7':
                $customerData['tax_rate'] = CollmexInvoice::TAX_RATE_REDUCED;
                break;

            case '19':
            default:
                $customerData['tax_rate'] = CollmexInvoice::TAX_RATE_FULL;
                break;
        }

        // fix value
        $customerData['client_id'] = $this->company;

        $customerData['language'] = CollmexInvoice::LANGUAGE_ENGLISH;

        if ($this->language == 1)
        {
            $customerData['language'] = CollmexInvoice::LANGUAGE_GERMAN;
        }

        $customerData['foreign_tax'] = $this->foreintax;
        $customerData['system_name'] = 'Conferenceplus';

        $customer = new CollmexInvoice($customerData);

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
