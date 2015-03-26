<?php

use Phinx\Migration\AbstractMigration;

class ConferenceplusCouponsInuse extends AbstractMigration
{
    protected $tableName = 'conferenceplus_coupons_inuse';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_coupon_inuse_id']);
        $table->addColumn('coupon_id', 'integer', ['comment' => 'id of a coupon'])
            ->addColumn('ticket_id', 'integer', ['comment' => 'id of the ticket'])
            ->addColumn('payment_id', 'integer', ['comment' => 'id of the payment'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addIndex(['coupon_id'], ['unique' => false])
            ->addIndex(['ticket_id'], ['unique' => false])
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