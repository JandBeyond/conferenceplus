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

// No direct access
defined('_JEXEC') or die;

class ConferenceplusTableTicket extends FOFTable
{
    /**
     * ConferenceplusTableTicket constructor.
     * @param string $table
     * @param string $key
     * @param JDatabaseDriver $db
     */
    public function __construct( $table, $key, &$db )
    {
        parent::__construct('#__conferenceplus_tickets', 'conferenceplus_ticket_id', $db);

        $fields = array('invoicecompany', 'invoicestreet', 'invoiceline2', 'invoicepcode', 'invoicecity', 'invoicecountry');

        foreach($fields as $field)
        {
            $this->addKnownField($field, true);
        }

    }
}