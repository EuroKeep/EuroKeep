<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MovementTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MovementTable Test Case
 */
class MovementTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MovementTable
     */
    protected $Movement;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Movement',
        'app.Category',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Movement') ? [] : ['className' => MovementTable::class];
        $this->Movement = $this->getTableLocator()->get('Movement', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Movement);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\MovementTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
