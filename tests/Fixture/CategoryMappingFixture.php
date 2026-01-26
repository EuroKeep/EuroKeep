<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoryMappingFixture
 */
class CategoryMappingFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'category_mapping';
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
                'created' => '2025-11-28 09:52:21',
                'modified' => '2025-11-28 09:52:21',
                'flags' => 1,
                'comment_mask' => 'Lorem ipsum dolor sit amet',
                'category_id' => 1,
            ],
        ];
        parent::init();
    }
}
