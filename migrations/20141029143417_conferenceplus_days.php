<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusDays extends AbstractMigration
{
    protected $tableName = 'u7jvr_conferenceplus_days';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_day_id']);
        $table->addColumn('name', 'string' , ['comment' => 'The Name of the Day'])
            ->addColumn('start', 'datetime', ['comment' => 'merged date and time for easier searching'])
            ->addColumn('sdate', 'date', ['comment' => 'The date of the day'])
            ->addColumn('stime', 'time', ['comment' => 'start time'])
            ->addColumn('stimeset', 'integer', ['comment' => 'is the start time set'])
            ->addColumn('etime', 'time', ['comment' => 'end time'])
            ->addColumn('etimeset', 'integer', ['comment' => 'is the end time set'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_day_id'], ['unique' => true])
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