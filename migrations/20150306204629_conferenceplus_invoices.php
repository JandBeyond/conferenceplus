<?php

use Phinx\Migration\AbstractMigration;

class ConferenceplusInvoices extends AbstractMigration
{
    protected $tableName = 'conferenceplus_invoices';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_invoice_id']);
        $table->addColumn('payment_id', 'integer', ['comment' => 'relation to a payment'])
            ->addColumn('identifier', 'string' , ['comment' => 'a uniq id for the invoice'])
            ->addColumn('data', 'text' , ['comment' => 'data for the invoice'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes it available'])
            ->addColumn('hash', 'string', ['comment' => 'used to allow people to change the address'])
            ->addIndex(['conferenceplus_invoice_id'], ['unique' => true])
            ->addIndex(['identifier'], ['unique' => true])
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