<?php
declare(strict_types=1);

namespace eurokeep\Model\Entity;

use Cake\ORM\Entity;

/**
 * Category Entity
 *
 * @property int $id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int $flags
 * @property string $emoji
 * @property string $name
 * @property int $parent_category_id
 *
 * @property \eurokeep\Model\Entity\Movement[] $movement
 */
class Category extends Entity
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
        'emoji' => true,
        'name' => true,
        'parent_category_id' => true,
        'movement' => true,
    ];
}
