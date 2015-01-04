<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusConfirmations extends AbstractMigration
{
    protected $tableName = 'bt12_conferenceplus_confirmations';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_confirmation_id']);
        $table->addColumn('description', 'text' , ['comment' => 'What are we confirming'])
            ->addColumn('type', 'integer', ['comment' => 'type of confirmation'])
            ->addColumn('hash', 'string', ['comment' => 'hash value to make the confimation uniq'])
            ->addColumn('confirmed', 'integer', ['comment' => 'confirmed yes (1) or no (0)'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_confirmation_id'], ['unique' => true])
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