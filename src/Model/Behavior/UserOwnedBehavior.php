<?php
declare(strict_types=1);

namespace eurokeep\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Query;

/**
 * UserOwned behavior
 */
class UserOwnedBehavior extends Behavior
{
    /**
     * @inheritDoc
     */
    public function beforeFind($event, Query $query, $options, $primary): void
    {
        $userId = $this->getConfig('user_id');
        if ($userId === null) {
            return;
        }

        $query->where([
            $this->_table->getAlias() . '.user_id' => $userId,
        ]);
    }

}
