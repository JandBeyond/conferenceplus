<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusSessions extends AbstractMigration
{
    protected $tableName = 'bt12_conferenceplus_sessions';

   
    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_session_id']);
        $table->addColumn('title', 'string' , ['comment' => 'Session Title'])
            ->addColumn('description', 'text', ['comment' => 'A description of the session'])
            ->addColumn('addidionalinfo', 'text', ['comment' => 'more information'])
            ->addColumn('catid', 'integer', ['comment' => 'category'])
            ->addColumn('speaker_multiple', 'string', ['comment' => 'indicator that we have more then one speaker'])
            ->addColumn('speaker_listtext', 'string', ['comment' => 'Name of the Speakers'])
            ->addColumn('speaker_listids', 'string', ['comment' => 'ids of speakers'])
            ->addColumn('state', 'integer', ['comment' => 'internal state for the submission'])
            ->addColumn('fstate', 'integer', ['comment' => 'final state for the submission'])
            ->addColumn('confirmed_by_speaker', 'integer', ['comment' => 'relation to the confirmation request'])
            ->addColumn('votes', 'text', ['comment' => 'votingsystem'])
            ->addColumn('notes', 'text', ['comment' => 'a note'])
            ->addColumn('log', 'text', ['comment' => 'action log'])
            ->addColumn('slides', 'text', ['comment' => 'embeded code for slides'])
            ->addColumn('video', 'text', ['comment' => 'embeded code for video'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the event available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_session_id'], ['unique' => true])
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