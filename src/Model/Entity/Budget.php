<?php
declare(strict_types=1);

namespace eurokeep\Model\Entity;

use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use eurokeep\Model\Table\CategoriesTable;
use eurokeep\Model\Table\MovementTable;
use Cake\Chronos\Chronos;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Budget Entity
 *
 * @property int $id
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property int $flags
 * @property string $name
 * @property float $limit
 * @property int $category_id
 * @property int $user_id
 * @property string $currency
 *
 * @property \eurokeep\Model\Entity\Category $category
 */
class Budget extends Entity
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
        'limit' => true,
        'category_id' => true,
        'user_id' => true,
        'currency' => true,
        'category' => true,
    ];

    public function getMovements(Chronos $start, Chronos $end)  {
        $movementTable = TableRegistry::getTableLocator()->get('Movement');

        /** @var CategoriesTable $categoriesTable */
        $categoriesTable = $movementTable->Categories;
        $category = $categoriesTable->get($this->category_id);

        return $category->getMovements($start, $end);
    }

    /**
     * @param Chronos $start Where the time begins.
     * @param Chronos $end Where it ends.
     * @return float I am the utilized value of the Budget.
     */
    public function getSpent(Chronos $start, Chronos $end): float
    {
        /** @var MovementTable $movementTable */
        $movementTable = TableRegistry::getTableLocator()->get('Movement');
        $query = $movementTable->find();

        $query = $query
            ->contain(['Account'])
            ->select([
                'total' => $query->func()->sum('Movement.balance_value')
            ])
            ->where([
                'Movement.balance_value <' => 0,
                'Account.balance_currency' => $this->currency
            ])
            ->matching('Categories.Budget', fn($q) => $q->where([
                'Budget.id' => $this->id,
            ]));

        if ($start) {
            $query->where(['Movement.created >=' => $start]);
        }

        if ($end) {
            $query->where(['Movement.created <=' => $end]);
        }

        return round((float)$query->first()->total, 2);
    }

    public static function summarize(array $movements): float
    {
        $a = 0;

        foreach($movements as $movement) {
            $a += round($movement->balance_value, 2);
        }

        return (float) $a;
    }
}
