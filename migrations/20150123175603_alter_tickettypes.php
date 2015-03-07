<?php

use Phinx\Migration\AbstractMigration;

class AlterTickettypes extends AbstractMigration
{
    protected $tableName = 'conferenceplus_tickettypes';

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
        $table->addColumn('productname', 'string', array('after' => 'name','comment' => 'The Name of the product, internal use'))
              ->update();
        
    }
}