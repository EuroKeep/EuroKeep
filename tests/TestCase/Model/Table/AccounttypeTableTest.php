<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AccounttypeTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccounttypeTable Test Case
 */
class AccounttypeTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AccounttypeTable
     */
    protected $Accounttype;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Accounttype',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Accounttype') ? [] : ['className' => AccounttypeTable::class];
        $this->Accounttype = $this->getTableLocator()->get('Accounttype', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Accounttype);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AccounttypeTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
