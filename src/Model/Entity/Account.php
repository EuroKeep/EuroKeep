<?php
declare(strict_types=1);

namespace eurokeep\Model\Entity;

use eurokeep\Model\Table\AccountTable;
use eurokeep\Model\Table\MovementTable;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use \DateTime;

/**
 * Account Entity
 *
 * @property int $id
 * @property DateTime|null $created
 * @property DateTime|null $modified
 * @property int $flags
 * @property string $name
 * @property string $emoji
 * @property string $iban
 * @property string $balance_value
 * @property string $balance_currency
 * @property int $accounttype_id
 * @property int $user_id
 *
 * @property Accounttype $accounttype
 * @property Movement[] $movement
 * @property User $user
 */
class Account extends Entity
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
        'emoji' => true,
        'iban' => true,
        'balance_value' => true,
        'balance_currency' => true,
        'accounttype_id' => true,
        'user_id' => true,
        'accounttype' => true,
        'movement' => true,
        'user' => true,
    ];

    /**
     * I will summarize the Movements on the Account and store the current balance to it.
     */
    public function summarize(): void
    {
        /** @var MovementTable $movementTable */
        $movementTable = TableRegistry::getTableLocator()->get('Movement');
        $value = $movementTable
            ->find()
            ->where([
                'account_id' => $this->get('id')
            ])
            ->all()
            ->sumOf('balance_value');

        $this->set('balance_value', $value);

        /** @var AccountTable $accountTable */
        $accountTable = TableRegistry::getTableLocator()->get('Account');
        $accountTable->save($this);
    }

    public function streamMovements(DateTime $start, DateTime $end) : array
    {
        $MovementTable = TableRegistry::getTableLocator()->get('Movement');
        return $MovementTable
            ->find()
            ->contain(['Categories'])
            ->where([
                'Movement.created >=' => $start->format('Y-m-d'),
                'Movement.created <' => $end->format('Y-m-d'),
                'account_id' => $this->id
            ])
            ->order(['Movement.created' => 'DESC'])
            ->limit(250)
            ->toArray();

    }

    public function settlement(array $movements): array {
        $settlement = [
            'expenses' => 0,
            'revenues' => 0,
            'settlement' => 0
        ];
        foreach($movements as $movement) {
            if($movement->balance_value < 0) {
                $settlement['expenses'] += $movement->balance_value;
            } else {
                $settlement['revenues'] += $movement->balance_value;
            }
            $settlement['settlement'] += $movement->balance_value;
        }

        return $settlement;
    }
}
