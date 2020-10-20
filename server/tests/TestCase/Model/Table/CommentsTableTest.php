<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CommentsTable;
use Cake\TestSuite\TestCase;
use Cake\Datasource\ConnectionManager;

/**
 * App\Model\Table\CommentsTable Test Case
 */
class CommentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CommentsTable
     */
    protected $Comments;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Comments',
        'app.GoogleAccounts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Comments') ? [] : ['className' => CommentsTable::class];
        $this->Comments = $this->getTableLocator()->get('Comments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Comments);

        parent::tearDown();
    }

    /**
     * Test method
     *
     * @return void
     */
    public function testDuplicateComments(): void
    {
        $connection = ConnectionManager::get('test');

        // Act.
        $connection->execute(
            # If a user posts 2 or more identical comments, mark them as spam.
            "UPDATE comments
            INNER JOIN google_accounts ON comments.google_account_id = google_accounts.id
            SET comments.status = 'SPAM', comments.updated_at = CURRENT_TIMESTAMP
            # Select all comments posted by this user with the same text.
            WHERE (SELECT COUNT(id) FROM (SELECT * FROM comments) AS comments2 WHERE comments.google_account_id = comments2.google_account_id AND comments.text = comments2.text) > 1"
        );
        $botComments = $connection->execute("SELECT * FROM comments WHERE google_account_id = 3")->fetchAll();

        // Assert.
        $this->assertEquals(2, count($botComments));
        foreach ($botComments as $comment)
        {
            $this->assertEquals('SPAM', $comment[5]);
        }
    }
}
