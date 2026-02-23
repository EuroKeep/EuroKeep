<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use Cake\ORM\Table;
use eurokeep\Model\Behavior\UserOwnedBehavior;
use Override;

/**
 * Account Model
 *
 * @mixin UserOwnedBehavior
 */
abstract class OwnedTable extends Table
{
    private static ?int $currentUserId = null;

    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     */
    #[Override]
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('UserOwned', [
            'user_id' => self::$currentUserId,
        ]);
    }

    /**
     * I will set the UserId of the owner of the Entities that inherit from me.
     * @param int|null $userId I am the id of the User that must own searched entities.
     */
    public static function setCurrentUserId(?int $userId): void
    {
        self::$currentUserId = $userId;
    }
}
