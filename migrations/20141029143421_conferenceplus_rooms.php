<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusRooms extends AbstractMigration
{
    protected $tableName = 'conferenceplus_rooms';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_room_id']);
        $table->addColumn('name', 'string' , ['comment' => 'The Name of the Room'])
            ->addColumn('maxseats', 'integer', ['comment' => 'max number of seats in this room'])
            ->addColumn('location', 'string' , ['comment' => 'Location of the Room'])
            ->addColumn('description', 'string' , ['comment' => 'A description of the Room'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_room_id'], ['unique' => true])
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