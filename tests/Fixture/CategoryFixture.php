<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoryFixture
 */
class CategoryFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'category';
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
                'created' => '2025-11-28 09:10:03',
                'modified' => '2025-11-28 09:10:03',
                'flags' => 1,
                'emoji' => 'Lorem ipsum do',
                'name' => 'Lorem ipsum dolor sit amet',
                'parent_category_id' => 1,
            ],
        ];
        parent::init();
    }
}
