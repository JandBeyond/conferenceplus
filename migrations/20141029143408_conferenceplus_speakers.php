<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusSpeakers extends AbstractMigration
{
    protected $tableName = 'u7jvr_conferenceplus_speakers';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_speaker_id']);
        $table->addColumn('firstname', 'string' , ['comment' => 'The Firstname of the Speaker'])
            ->addColumn('lastname', 'string' , ['comment' => 'The Lastname of the Speaker'])
            ->addColumn('email', 'string', ['comment' => 'Speaker email'])
            ->addColumn('bio', 'text', ['comment' => 'about the speaker'])
            ->addColumn('notes', 'text', ['comment' => 'Some notes'])
            ->addColumn('imagefile', 'text', ['comment' => 'image'])
            ->addColumn('userid', 'integer', ['comment' => 'Joomla User id if a joomla user exists'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_speaker_id'], ['unique' => true])
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