<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusSessionTypes extends AbstractMigration
{
    protected $tableName = 'u7jvr_conferenceplus_sessiontypes';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_sessiontype_id']);
        $table->addColumn('name', 'string' , ['comment' => 'The Name of the Session type'])
            ->addColumn('length', 'integer', ['comment' => 'lenght in minutes'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_sessiontype_id'], ['unique' => true])
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