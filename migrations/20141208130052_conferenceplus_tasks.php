<?php

use Phinx\Migration\AbstractMigration;

class ConferenceplusTasks extends AbstractMigration
{
    protected $tableName = 'conferenceplus_tasks';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_task_id']);
        $table->addColumn('name', 'string', ['comment' => 'human readable name of task'])
            ->addColumn('processdata', 'text' , ['comment' => 'data to process'])
            ->addColumn('resultmessage', 'text', ['comment' => 'Result messages'])
            ->addColumn('result', 'integer', ['comment' => 'THE Result 1 == success'])
            ->addColumn('started', 'datetime', ['comment' => 'processing started'])
            ->addColumn('finished', 'datetime', ['comment' => 'processing finished, does not say something successful'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_task_id'], ['unique' => true])
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