<?php

use Phinx\Migration\AbstractMigration;

class ConferenceplusAwardvotes extends AbstractMigration
{
    protected $tableName = 'u7jvr_conferenceplus_awardvotes';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_awardvote_id']);
        $table->addColumn('nominee', 'integer', ['comment' => 'relation to a nominated'])
            ->addColumn('votes', 'integer', ['comment' => 'votes'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the event available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realation to an event'])
            ->addIndex(['conferenceplus_awardvote_id'], ['unique' => true])
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