<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Settings extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        // Subscriptions
        if ($this->hasTable('users')) {
            $this->table('users')
                ->addColumn('settings', 'json', [
                    'default' => '{}',
                    'null' => false,
                ])
                ->update();
        }

    }
}
