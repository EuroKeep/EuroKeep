<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AccounttypeFixture
 */
class AccounttypeFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'accounttype';
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
                'created' => '2026-01-22 11:17:45',
                'modified' => '2026-01-22 11:17:45',
                'flags' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
