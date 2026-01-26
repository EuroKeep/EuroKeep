<?php
declare(strict_types=1);

namespace eurokeep\Model\Entity;

use eurokeep\Model\Table\AccountTable;
use eurokeep\Model\Table\MovementTable;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Account Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $flags
 * @property string $name
 * @property string $emoji
 * @property string $iban
 * @property float $balance_value
 * @property string $balance_currency
 * @property int $accounttype_id
 * @property int $user_id
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
    ];

    /**
     * I will summarize the Movements on the Account and store the current balance to it.
     * @return void
     */
    public function summarize(): void
    {
        /** @var MovementTable $MovementsTable */
        $MovementsTable = TableRegistry::getTableLocator()->get('Movement');
        $value = $MovementsTable
            ->find()
            ->where([
                'account_id' => $this->get('id')
            ])
            ->all()
            ->sumOf('balance_value');

        $this->set('balance_value', $value);

        /** @var AccountTable $AccountTable */
        $AccountTable = TableRegistry::getTableLocator()->get('Account');
        $AccountTable->save($this);
    }
}
