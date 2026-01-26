<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MovementFixture
 */
class MovementFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'movement';
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
                'created' => '2025-12-04 09:50:19',
                'modified' => '2025-12-04 09:50:19',
                'flags' => 1,
                'balance_value' => 1,
                'balance_currency' => 'Lorem ipsum dolor sit amet',
                'comment' => 'Lorem ipsum dolor sit amet',
                'category_id' => 1,
                'account_id' => 1,
                'user_id' => 1,
                'transfer_id' => 1,
            ],
        ];
        parent::init();
    }
}
