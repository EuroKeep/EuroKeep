<?php
declare(strict_types=1);

namespace eurokeep\Model\Entity;

use Cake\ORM\Entity;

/**
 * CategoryMapping Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $flags
 * @property string $comment_mask
 * @property int|null $category_id
 */
class CategoryMapping extends Entity
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
        'comment_mask' => true,
        'category_id' => true,
    ];
}
