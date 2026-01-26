<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoryMappingTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CategoryMappingTable Test Case
 */
class CategoryMappingTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CategoryMappingTable
     */
    protected $CategoryMapping;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.CategoryMapping',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('CategoryMapping') ? [] : ['className' => CategoryMappingTable::class];
        $this->CategoryMapping = $this->getTableLocator()->get('CategoryMapping', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->CategoryMapping);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\CategoryMappingTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
