<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Accounting extends AbstractMigration
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
     * @return void
     */
    public function change(): void
    {
        // Accounttype
        if (!$this->hasTable('accounttype')) {
            $this->table('accounttype')
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
                ->create();
        }

        // Account
        if (!$this->hasTable('account')) {
            $this->table('account')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 10,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('created', 'datetime', [
                    'default' => null,
                    'limit'   => null,
                    'null'    => true,
                ])
                ->addColumn('modified', 'datetime', [
                    'default' => null,
                    'limit'   => null,
                    'null'    => true,
                ])
                ->addColumn('flags', 'integer', [
                    'default' => '0',
                    'limit'   => 6,
                    'null'    => false,
                ])
                ->addColumn('name', 'string', [
                    'default' => null,
                    'limit'   => 100,
                    'null'    => false,
                ])
                ->addColumn('emoji', 'string', [
                    'default' => null,
                    'limit'   => 8,
                    'null'    => false,
                ])
                ->addColumn('iban', 'string', [
                    'default' => null,
                    'limit'   => 50,
                    'null'    => false,
                ])
                ->addColumn('balance_value', 'decimal', [
                    'default' => null,
                    'precision' => 15,
                    'scale' => 2,
                    'null'    => false,
                ])
                ->addColumn('balance_currency', 'string', [
                    'default' => null,
                    'limit'   => 100,
                    'null'    => false,
                ])
                ->addColumn('accounttype_id', 'integer', [
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

        // Category
        if (!$this->hasTable('category')) {
            $this->table('category')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 10,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('created', 'datetime', [
                    'default' => 'CURRENT_TIMESTAMP',
                    'limit'   => null,
                    'null'    => true,
                ])
                ->addColumn('modified', 'datetime', [
                    'default' => 'CURRENT_TIMESTAMP',
                    'limit'   => null,
                    'null'    => true,
                ])
                ->addColumn('flags', 'integer', [
                    'default' => 0,
                    'limit'   => 6,
                    'null'    => false,
                ])
                ->addColumn('emoji', 'string', [
                    'default' => null,
                    'limit'   => 16,
                    'null'    => false,
                ])
                ->addColumn('name', 'string', [
                    'default' => null,
                    'limit'   => 128,
                    'null'    => false,
                ])
                ->addColumn('parent_category_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => true,
                ])
                ->create();
        }

        // Movement
        if (!$this->hasTable('movement')) {
            $this->table('movement')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 10,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('created', 'datetime', [
                    'default' => 'CURRENT_TIMESTAMP',
                    'limit'   => null,
                    'null'    => true,
                ])
                ->addColumn('modified', 'datetime', [
                    'default' => 'CURRENT_TIMESTAMP',
                    'limit'   => null,
                    'null'    => true,
                ])
                ->addColumn('flags', 'integer', [
                    'default' => 0,
                    'limit'   => 6,
                    'null'    => false,
                ])
                ->addColumn('balance_value', 'decimal', [
                    'default' => null,
                    'precision' => 15,
                    'scale' => 2,
                    'null'    => false,
                ])
                ->addColumn('comment', 'string', [
                    'default' => null,
                    'limit'   => 100,
                    'null'    => false,
                ])
                ->addColumn('category_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => true,
                ])
                ->addColumn('account_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('user_id', 'integer', [
                    'default' => null,
                    'limit'   => 11,
                    'null'    => false,
                ])
                ->addColumn('transfer_id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => true,
                ])
                ->create();
        }
    }
}
