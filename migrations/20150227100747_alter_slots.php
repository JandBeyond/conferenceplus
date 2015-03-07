<?php

use Phinx\Migration\AbstractMigration;

class AlterSlots extends AbstractMigration
{
    protected $tableName = 'bt12_conferenceplus_slots';

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
     */
    public function change()
    {
        
        $table = $this->table($this->tableName);
        $table->addColumn('stimeset', 'integer', array('after' => 'start','comment' => 'Is the start time set','null' => false))
            ->addColumn('stimeset', 'integer', array('after' => 'end','comment' => 'Is the start time set','null' => false))
            ->renameColumn('start', 'stime')
            ->renameColumn('end', 'etime')
            ->renameColumn('room_id', 'slottype',array('comment' => 'Type of session 0 = normal, 1 = span over rooms'))
            ->update();
    }
}
