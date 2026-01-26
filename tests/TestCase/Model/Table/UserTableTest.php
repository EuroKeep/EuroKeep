<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UserTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UserTable
     */
    protected $User;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.User',
        'app.Account',
        'app.Movement',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('User') ? [] : ['className' => UserTable::class];
        $this->User = $this->getTableLocator()->get('User', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->User);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
