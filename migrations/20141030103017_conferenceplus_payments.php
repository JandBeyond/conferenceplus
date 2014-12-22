<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusPayments extends AbstractMigration
{
    protected $tableName = 'u7jvr_conferenceplus_payments';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_payment_id']);
        $table->addColumn('name', 'string' , ['comment' => 'The Name provided with the payment'])
            ->addColumn('processdata', 'text', ['comment' => 'Process Data as json'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_payment_id'], ['unique' => true])
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