<?php
declare(strict_types=1);

namespace eurokeep\Model\Entity;

use Cake\Chronos\Chronos;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use eurokeep\Model\Table\CategoriesTable;

/**
 * Category Entity
 *
 * @property int $id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int $flags
 * @property string $emoji
 * @property string $name
 * @property int|null $parent_id
 * @property int $lft
 * @property int $rght
 *
 * @property \eurokeep\Model\Entity\Budget[] $budget
 * @property \eurokeep\Model\Entity\Movement[] $movement
 * @property \eurokeep\Model\Entity\Subscription[] $subscription
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
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'budget' => true,
        'movement' => true,
        'subscription' => true,
    ];

    /**
     * I will return all Categories that are the direct children of this Category.
     * @return ResultSetInterface The list of Categories that are children of this Category.
     */
    public function getChildren(bool $direct = true): ResultSetInterface
    {
        return TableRegistry::getTableLocator()
            ->get('Categories')
            ->find('children', [
                'for' => $this->id,
                'direct' => $direct
            ])
            ->all();
    }

    /**
     * I will find all Movements for this category or for the nested child Categories.
     * @param Chronos $start I am the start of the time range where the Movements will be searched.
     * @param Chronos $end I am the end of the time range where the Movements will be searched.
     * @return ResultSetInterface I am the list of Movements found in the range.
     */
    public function getMovements(Chronos $start, Chronos $end): ResultSetInterface
    {
        $accountIds = TableRegistry::getTableLocator()
            ->get('Account')
            ->find()
            ->all()
            ->extract('id')
            ->toArray();

        $descendants = $this
            ->getChildren(false)
            ->extract('id')
            ->toList();
        $descendants[] = $this->id;

        return TableRegistry::getTableLocator()
            ->get('Movement')
            ->find()
            ->where([
                'Account.id IN' => $accountIds,
                'Movement.category_id IN' => $descendants,
                'Movement.created >=' => $start,
                'Movement.created <' => $end,
#                'Movement.balance_value <' => 0
            ])
            ->contain(['Account', 'Categories'])
            ->orderBy(['Movement.created' => 'DESC'])
            ->all();
    }
}
