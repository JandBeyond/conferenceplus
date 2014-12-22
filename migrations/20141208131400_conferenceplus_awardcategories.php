<?php

use Phinx\Migration\AbstractMigration;

class ConferenceplusAwardcategories extends AbstractMigration
{
    protected $tableName = 'u7jvr_conferenceplus_awardcategories';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_awardcategory_id']);
        $table->addColumn('name', 'string' , ['comment' => 'The Name of the award category'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_awardcategory_id'], ['unique' => true])
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