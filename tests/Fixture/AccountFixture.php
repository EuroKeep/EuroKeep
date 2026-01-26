<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AccountFixture
 */
class AccountFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'account';
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
                'created' => '2025-11-19 21:27:57',
                'modified' => '2025-11-19 21:27:57',
                'flags' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'emoji' => 'Lorem ',
                'iban' => 'Lorem ipsum dolor sit amet',
                'balance_value' => 1.5,
                'balance_currency' => 'Lorem ipsum dolor sit amet',
                'accounttype_id' => 1,
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
