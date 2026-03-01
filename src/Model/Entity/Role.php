<?php
declare(strict_types=1);

namespace eurokeep\Model\Entity;

use Cake\I18n\DateTime;
use Cake\ORM\Entity;

/**
 * Role Entity
 *
 * @property int $id
 * @property DateTime $created
 * @property DateTime $modified
 * @property int $flags
 * @property string $name
 *
 * @property User[] $users
 */
class Role extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'created' => true,
        'modified' => true,
        'flags' => true,
        'name' => true,
        'users' => true,
    ];
}
