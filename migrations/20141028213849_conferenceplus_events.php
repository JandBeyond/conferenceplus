<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusEvents extends AbstractMigration
{
    protected $tableName = 'conferenceplus_events';

   /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_event_id']);
        $table->addColumn('name', 'string' , ['comment' => 'The Name of the Event'])
            ->addColumn('description', 'text', ['comment' => 'A description of the event'])
            ->addColumn('venuename', 'string', ['comment' => 'Name of the venue'])
            ->addColumn('street', 'string' , ['comment' => 'The name of the street where the event will taken place'])
            ->addColumn('city', 'string' , ['comment' => 'The name of the city where the event will taken place'])
            ->addColumn('country', 'string' , ['comment' => 'The name of the country where the event will taken place'])
            ->addColumn('start', 'datetime', ['comment' => 'merged date and time for easier searching'])
            ->addColumn('sdate', 'date', ['comment' => 'Startdate of the event'])
            ->addColumn('stime', 'time', ['comment' => 'start time'])
            ->addColumn('stimeset', 'integer', ['comment' => 'is the start time set'])
            ->addColumn('end', 'datetime', ['comment' => 'merged date and time for easier searching'])
            ->addColumn('edate', 'date', ['comment' => 'enddate of the event'])
            ->addColumn('etime', 'time', ['comment' => 'end time'])
            ->addColumn('etimeset', 'integer', ['comment' => 'is the end time set'])
            ->addColumn('contactemail', 'string', ['comment' => 'Emailaddress to contact the team'])
            ->addColumn('total_number_attendees', 'integer', ['comment' => 'The total number of attendees for this event'])
            ->addColumn('params', 'text', ['comment' => 'Event Params'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the event available'])
            ->addIndex(['conferenceplus_event_id'], ['unique' => true])
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