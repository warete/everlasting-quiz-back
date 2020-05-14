<?php

use Phinx\Migration\AbstractMigration;

class AddQuestionsColumns extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
		$table = $this->table('questions');
		$table->addColumn('category_id', 'integer')
//			->addForeignKey('category_id', 'categories', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
			->addColumn('text', 'string')
			->addColumn('answer_1', 'string')
			->addColumn('answer_2', 'string')
			->addColumn('answer_3', 'string')
			->addColumn('answer_4', 'string')
			->addColumn('version_hash', 'string')
			->addColumn('created', 'datetime')
			->update();
    }
}
