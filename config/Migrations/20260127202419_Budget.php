<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Budget extends AbstractMigration
{
    /**
     * Whether the tables created in this migration
     * should auto-create an `id` field or not
     *
     * This option is global for all tables created in the migration file.
     * If you set it to false, you have to manually add the primary keys for your
     * tables using the Migrations\Table::addPrimaryKey() method
     *
     * @var bool
     */
    public bool $autoId = false;


    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     */
    public function change(): void
    {
        // Budget
        if (!$this->hasTable('budget')) {
            $this->table('budget')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('created', 'datetime', [
                    'default' => 'CURRENT_TIMESTAMP',
                    'limit' => null,
                    'null' => false,
                ])
                ->addColumn('modified', 'datetime', [
                    'default' => 'CURRENT_TIMESTAMP',
                    'limit' => null,
                    'null' => false,
                ])
                ->addColumn('flags', 'integer', [
                    'default' => 0,
                    'limit' => 6,
                    'null' => false,
                ])
                ->addColumn('name', 'string', [
                    'default' => null,
                    'limit' => 128,
                    'null' => false,
                ])
                ->addColumn('limit', 'decimal', [
                    'default' => 0,
                    'precision' => 15,
                    'scale' => 2,
                    'null'    => false,
                ])
                ->addColumn('currency', 'string', [
                    'default' => null,
                    'limit' => 128,
                    'null' => false,
                ])
                ->addColumn('category_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('user_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->create();
        }
    }
}
