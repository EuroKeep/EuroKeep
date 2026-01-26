<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserFixture
 */
class UserFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'user';
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
                'created' => '2026-01-13 09:54:43',
                'modified' => '2026-01-13 09:54:43',
                'flags' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 1,
            ],
        ];
        parent::init();
    }
}
