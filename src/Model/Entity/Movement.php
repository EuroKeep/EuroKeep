<?php
declare(strict_types=1);

namespace eurokeep\Model\Entity;

use eurokeep\Model\Table\AccountTable;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Movement Entity
 *
 * @property int $id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int $flags
 * @property string $balance_value
 * @property string $comment
 * @property int|null $category_id
 * @property int $account_id
 *
 * @property \eurokeep\Model\Entity\Category $category
 * @property \eurokeep\Model\Entity\Account $account
 */
class Movement extends Entity
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
        'balance_value' => true,
        'comment' => true,
        'category_id' => true,
        'account_id' => true,
        'category' => true,
        'account' => true,
    ];

    final public function getAccount(): Account
    {
        /** @var AccountTable $accountTable */
        $accountTable = TableRegistry::getTableLocator()->get('Account');
        return $accountTable->get($this->get('account_id'));
    }
}
