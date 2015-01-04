<?php

use Phinx\Migration\AbstractMigration;

class conferenceplusSlots extends AbstractMigration
{
    protected $tableName = 'bt12_conferenceplus_slots';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_slot_id']);
        $table->addColumn('name', 'string' , ['comment' => 'The Name of the Slot'])
            ->addColumn('description', 'text', ['comment' => 'A description of the slot'])
            ->addColumn('start', 'time', ['comment' => 'Starttime'])
            ->addColumn('end', 'time', ['comment' => 'endtime'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('room_id', 'integer', ['comment' => 'relation to the room'])
            ->addColumn('day_id', 'integer', ['comment' => 'relation to the day'])
            ->addIndex(['conferenceplus_slot_id'], ['unique' => true])
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