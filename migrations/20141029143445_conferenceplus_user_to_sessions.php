<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusUserToSessions extends AbstractMigration
{
    protected $tableName = 'conferenceplus_user_to_sessions';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_user_to_session_id']);
        $table->addColumn('user_id', 'integer', ['comment' => 'user'])
            ->addColumn('session_id', 'integer', ['comment' => 'session'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addIndex(['conferenceplus_user_to_session_id'], ['unique' => true])
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