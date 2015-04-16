<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusDefaults extends AbstractMigration
{
    protected $tableName = 'conferenceplus_defaults';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_default_id']);
        $table->addColumn('configuration', 'text' , ['comment' => 'Configuration'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_default_id'], ['unique' => true])
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