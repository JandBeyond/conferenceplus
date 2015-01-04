<?php

use Phinx\Migration\AbstractMigration;

class ConferenceplusEmailtemplates extends AbstractMigration
{
    protected $tableName = 'bt12_conferenceplus_emailtemplates';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table($this->tableName, ['id' => 'conferenceplus_emailtemplate_id']);
        $table->addColumn('name', 'string', ['comment' => 'name of emailtemplate'])
            ->addColumn('description', 'text' , ['comment' => 'What'])
            ->addColumn('from_email', 'string', ['comment' => 'Email Form'])
            ->addColumn('from_name', 'string', ['comment' => 'EMail Name'])
            ->addColumn('subject', 'string', ['comment' => 'EMail Subject'])
            ->addColumn('text', 'text', ['comment' => 'EMail text'])
            ->addColumn('html', 'text', ['comment' => 'EMail html'])
            ->addColumn('created', 'datetime', ['comment' => 'Entry created'])
            ->addColumn('modified', 'datetime', ['comment' => 'Last modification'])
            ->addColumn('enabled', 'integer', ['comment' => 'makes the item available'])
            ->addColumn('event_id', 'integer', ['comment' => 'realtion to an event'])
            ->addIndex(['conferenceplus_emailtemplate_id'], ['unique' => true])
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