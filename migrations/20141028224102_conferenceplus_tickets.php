<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusTickets extends AbstractMigration
{
    protected $tableName = 'conferenceplus_tickets';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_ticket_id']);
        $table->addColumn('tickettype_id', 'integer' , ['comment' => 'realtion to the Tickettype'])
            ->addColumn('payment_id', 'integer' , ['comment' => 'realtion to the Payment'])
            ->addColumn('processdata', 'text', ['comment' => 'Process Data as json'])
            ->addColumn('firstname', 'string' , ['comment' => 'The Firstname'])
            ->addColumn('lastname', 'string' , ['comment' => 'The Lastname'])
            ->addColumn('email', 'string', ['comment' => 'Email'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addIndex(['conferenceplus_ticket_id'], ['unique' => true])
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