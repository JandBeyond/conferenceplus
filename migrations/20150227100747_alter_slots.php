<?php

use Phinx\Migration\AbstractMigration;

class AlterSlots extends AbstractMigration
{
    protected $tableName = 'conferenceplus_slots';

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
        $table->renameColumn('start', 'stime')
            ->renameColumn('end', 'etime')
            ->renameColumn('room_id', 'slottype',array('comment' => 'Type of session 0 = normal, 1 = span over rooms'))
            ->addColumn('stimeset', 'integer', array('after' => 'stime','comment' => 'Is the start time set','null' => false))
            ->addColumn('etimeset', 'integer', array('after' => 'etime','comment' => 'Is the start time set','null' => false))
            ->update();
    }
}
