<?php

use Phinx\Migration\AbstractMigration;

class ConferenceplusAwardnominations extends AbstractMigration
{
    protected $tableName = 'bt12_conferenceplus_awardnominations';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_awardnomination_id']);
        $table->addColumn('firstname', 'string' , ['comment' => 'Nominated by FirstName'])
            ->addColumn('lastname', 'string' , ['comment' => 'Nominated by LastName'])
            ->addColumn('email', 'string', ['comment' => 'Nominated by email'])
            ->addColumn('nominee', 'string', ['comment' => 'who/what is nominated'])
            ->addColumn('awardcategory_id', 'integer', ['comment' => 'category id'])
            ->addColumn('shortlist', 'integer', ['comment' => 'is on the final shortlist'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the event available'])
            ->addIndex(['conferenceplus_awardnomination_id'], ['unique' => true])
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