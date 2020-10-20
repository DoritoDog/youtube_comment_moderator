<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Comment Entity
 *
 * @property int $id
 * @property int $google_account_id
 * @property string $text
 * @property string $youtube_id
 * @property string $url
 * @property \Cake\I18n\FrozenTime|null $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 *
 * @property \App\Model\Entity\GoogleAccount $google_account
 * @property \App\Model\Entity\Youtube $youtube
 */
class Comment extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'google_account_id' => true,
        'text' => true,
        'youtube_id' => true,
        'url' => true,
        'created_at' => true,
        'updated_at' => true,
        'google_account' => true,
        'video' => true
    ];
}
