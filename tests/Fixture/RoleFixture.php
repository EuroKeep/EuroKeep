<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RoleFixture
 */
class RoleFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'role';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'created' => '2026-01-18 23:37:48',
                'modified' => '2026-01-18 23:37:48',
                'flags' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
