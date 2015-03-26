<?php

use Phinx\Migration\AbstractMigration;

class ConferenceplusCoupons extends AbstractMigration
{
    protected $tableName = 'conferenceplus_coupons';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_coupon_id']);
        $table->addColumn('identifier', 'string' , ['comment' => 'a uniq id for the coupon'])
            ->addColumn('name', 'string' , ['comment' => 'name for the coupon, just an internal name for reference'])
            ->addColumn('description', 'text' , ['comment' => 'a description for the coupon'])

            ->addColumn('sdate', 'date', ['comment' => 'Startdate of the event'])
            ->addColumn('stime', 'time', ['comment' => 'start time'])
            ->addColumn('stimeset', 'integer', ['comment' => 'is the start time set'])
            ->addColumn('end', 'datetime', ['comment' => 'merged date and time for easier searching'])
            ->addColumn('edate', 'date', ['comment' => 'enddate of the event'])
            ->addColumn('etime', 'time', ['comment' => 'end time'])
            ->addColumn('etimeset', 'integer', ['comment' => 'is the end time set'])

            ->addColumn('number_valid_items', 'integer', ['comment' => 'number of coupons that can be used, zero for unlimited'])

            ->addColumn('freeticket', 'integer', ['comment' => 'sets the fee to zero (priority 1)'])
            ->addColumn('fixed_fee', 'integer', ['comment' => 'a fixed fee / 100 no float for the ticket (priority 2)'])    
            ->addColumn('discount_fix', 'integer', ['comment' => 'a fixed discount / 100 no float on the ticket fee (priority 3)'])                
            ->addColumn('discount_percentaged', 'integer', ['comment' => 'a percentaged discount on the ticket fee (priority 4)'])                

            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes it available'])
            ->addIndex(['conferenceplus_coupon_id'], ['unique' => true])
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