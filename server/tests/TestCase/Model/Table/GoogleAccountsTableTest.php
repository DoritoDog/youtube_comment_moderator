<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GoogleAccountsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GoogleAccountsTable Test Case
 */
class GoogleAccountsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GoogleAccountsTable
     */
    protected $GoogleAccounts;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.GoogleAccounts',
        'app.Comments',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('GoogleAccounts') ? [] : ['className' => GoogleAccountsTable::class];
        $this->GoogleAccounts = $this->getTableLocator()->get('GoogleAccounts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->GoogleAccounts);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
