<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusMessages extends AbstractMigration
{
    protected $tableName = 'u7jvr_conferenceplus_messages';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_message_id']);
        $table->addColumn('name', 'string' , ['comment' => 'The Name of the Message'])
            ->addColumn('to_email', 'string', ['comment' => 'Email to'])
            ->addColumn('to_name', 'string', ['comment' => 'EMail Name'])
            ->addColumn('subject', 'string', ['comment' => 'EMail Subject'])
            ->addColumn('message', 'text', ['comment' => 'message'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_message_id'], ['unique' => true])
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