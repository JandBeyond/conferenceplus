<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusTickettypes extends AbstractMigration
{
    protected $tableName = 'u7jvr_conferenceplus_tickettypes';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_tickettype_id']);
        $table->addColumn('name', 'string' , ['comment' => 'The Name of the Tickettype'])
            ->addColumn('description', 'text', ['comment' => 'A description of the tickettype'])
            ->addColumn('fee', 'integer' , ['comment' => 'Ticket fee * 100 no float'])
            ->addColumn('vat', 'integer' , ['comment' => 'VAT for the ticket'])
            ->addColumn('total_number_of_ticktes_available', 'integer' , ['comment' => 'Ticket available'])
            ->addColumn('partnerticket', 'integer', ['comment' => 'includes a special ticket for fun events after the conf'])
            ->addColumn('valid_from', 'datetime', ['comment' => 'From time to buy a ticket'])
            ->addColumn('valid_to', 'datetime', ['comment' => 'To time to buy a ticket'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_tickettype_id'], ['unique' => true])
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable($this->tableName);
    }
}