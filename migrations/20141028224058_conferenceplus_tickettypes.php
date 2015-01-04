<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusTickettypes extends AbstractMigration
{
    protected $tableName = 'bt12_conferenceplus_tickettypes';

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
            ->addColumn('sdate', 'date', ['comment' => 'Startdate of the event'])
            ->addColumn('stime', 'time', ['comment' => 'start time'])
            ->addColumn('stimeset', 'integer', ['comment' => 'is the start time set'])
            ->addColumn('end', 'datetime', ['comment' => 'merged date and time for easier searching'])
            ->addColumn('edate', 'date', ['comment' => 'enddate of the event'])
            ->addColumn('etime', 'time', ['comment' => 'end time'])
            ->addColumn('etimeset', 'integer', ['comment' => 'is the end time set'])
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