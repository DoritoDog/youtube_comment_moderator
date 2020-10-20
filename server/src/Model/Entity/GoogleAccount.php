<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GoogleAccount Entity
 *
 * @property int $id
 * @property string $username
 * @property string $avatar_url
 *
 * @property \App\Model\Entity\Comment[] $comments
 */
class GoogleAccount extends Entity
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
        'username' => true,
        'avatar_url' => true,
        'comments' => true,
        'status' => true,
    ];
}
