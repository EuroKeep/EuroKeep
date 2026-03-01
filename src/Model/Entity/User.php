<?php
declare(strict_types=1);

namespace eurokeep\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\I18n\FrozenTime;
use eurokeep\Model\Table\AccountTable;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * User Entity
 *
 * @property int $id
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property int $flags
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $role_id
 *
 * @property Account[] $account
 * @property Movement[] $movement
 */
class User extends Entity
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
        'email' => true,
        'password' => true,
        'account' => true,
        'movement' => true,
        'role_id' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected array $_hidden = [
        'password',
    ];

    /**
     * @param string $password
     * @return string
     */
    protected function _setPassword(string $password) : string {
        $hasher = new DefaultPasswordHasher();

        return $hasher->hash($password);
    }

    /**
     * @return array
     */
    final public function getTotalBalances() : array {
        /** @var AccountTable $accountTable */
        $accountTable = TableRegistry::getTableLocator()->get('Account');

        // Full Balance
        $qry = $accountTable->find();
        $qry->where(['user_id' => $this->id])
            ->select([
                'balance_currency',
                'total_balance' => $qry->func()->sum('balance_value')
            ])
            ->group(['balance_currency']);

        $sum = $qry->toArray();


        $totalBalances = [];
        foreach($sum as $row){
            $totalBalances[] = [
                'currency' =>$row['balance_currency'],
                'value' => $row['total_balance'],
            ];
        }

        return $totalBalances;
    }
}
