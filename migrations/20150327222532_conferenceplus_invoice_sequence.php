<?php

use Phinx\Migration\AbstractMigration;

class ConferenceplusInvoiceSequence extends AbstractMigration
{
    protected $tableName = 'conferenceplus_invoice_sequence';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'id']);
        $table->addIndex(['id'], ['unique' => true])
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