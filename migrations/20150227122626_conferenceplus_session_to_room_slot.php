<?php

use Phinx\Migration\AbstractMigration;

class ConferenceplusSessionToRoomSlot extends AbstractMigration
{
    protected $tableName = 'conferenceplus_sessions_to_rooms_slots';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_session_to_room_id']);
        $table->addColumn('session_id', 'integer', ['comment' => 'session'])
            ->addColumn('room_id', 'integer', ['comment' => 'room'])
            ->addColumn('slot_id', 'integer', ['comment' => 'slot'])
            ->addIndex(['conferenceplus_session_to_room_id'], ['unique' => true])
            ->addIndex(['session_id'])
            ->addIndex(['room_id'])
            ->addIndex(['slot_id'])
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