<?php

use Phinx\Migration\AbstractMigration;

class ConferenceplusCouponsToTickettype extends AbstractMigration
{
    protected $tableName = 'conferenceplus_coupons_to_tickettypes';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_coupon_to_tickettype_id']);
        $table->addColumn('coupon_id', 'integer', ['comment' => 'id of a coupon'])
            ->addColumn('tickettype_id', 'integer', ['comment' => 'id of the tickettype'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addIndex(['coupon_id'], ['unique' => false])
            ->addIndex(['tickettype_id'], ['unique' => false])
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