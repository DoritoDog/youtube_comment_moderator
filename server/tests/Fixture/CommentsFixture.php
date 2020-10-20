<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CommentsFixture
 */
class CommentsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'google_account_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'text' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null],
        'youtube_id' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null],
        'url' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null],
        'status' => ['type' => 'string'],
        'created_at' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => 'current_timestamp()', 'comment' => ''],
        'updated_at' => ['type' => 'datetime', 'length' => null, 'precision' => null, 'null' => true, 'default' => 'current_timestamp()', 'comment' => ''],
        '_indexes' => [
            'google_account_id' => ['type' => 'index', 'columns' => ['google_account_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'comments_ibfk_1' => ['type' => 'foreign', 'columns' => ['google_account_id'], 'references' => ['google_accounts', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci'
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'google_account_id' => 1,
                'text' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'status' => 'NEW',
            ],
            [
                'google_account_id' => 2,
                'text' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Error, maiores!',
                'status' => 'NEW',
            ],
            [
                'google_account_id' => 1,
                'text' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit, dolores. Dolorum id aut magnam consectetur.',
                'status' => 'NEW',
            ],
            [
                'google_account_id' => 3,
                'text' => 'So underrated [LINK]',
                'status' => 'NEW',
            ],
            [
                'google_account_id' => 2,
                'text' => 'Lorem ipsum dolor sit amet.',
                'status' => 'NEW',
            ],
            [
                'google_account_id' => 3,
                'text' => 'So underrated [LINK]',
                'status' => 'NEW',
            ],
        ];
        parent::init();
    }
}
