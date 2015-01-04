<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusAttendees extends AbstractMigration
{
    protected $tableName = 'bt12_conferenceplus_attendees';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_attendee_id']);
        $table->addColumn('firstname', 'string' , ['comment' => 'The Firstname'])
            ->addColumn('lastname', 'string' , ['comment' => 'The Lastname'])
            ->addColumn('email', 'string', ['comment' => 'Email'])
            ->addColumn('gender', 'string', ['comment' => 'Gender info'])
            ->addColumn('food', 'string', ['comment' => 'Food preference'])
            ->addColumn('tshirtsize', 'string', ['comment' => 'T-Shirt Size'])
            ->addColumn('ticket_id', 'integer', ['comment' => 'relation to the ticket_id'])
            ->addColumn('userid', 'integer', ['comment' => 'Joomla User id if a joomla user exists'])
            ->addColumn('partner', 'integer', ['comment' => 'Takes only part on the fun events'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addIndex(['conferenceplus_attendee_id'], ['unique' => true])
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