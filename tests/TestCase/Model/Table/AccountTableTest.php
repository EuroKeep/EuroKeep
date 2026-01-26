<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AccountTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccountTable Test Case
 */
class AccountTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AccountTable
     */
    protected $Account;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Account',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Account') ? [] : ['className' => AccountTable::class];
        $this->Account = $this->getTableLocator()->get('Account', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Account);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AccountTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
