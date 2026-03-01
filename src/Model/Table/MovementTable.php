<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\ResultSet;
use Closure;
use eurokeep\Model\Entity\Movement;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Override;
use Psr\SimpleCache\CacheInterface;

/**
 * Movement Model
 *
 * @method Movement newEmptyEntity()
 * @method Movement newEntity(array $data, array $options = [])
 * @method array<Movement> newEntities(array $data, array $options = [])
 * @method Movement get(mixed $primaryKey, array|string $finder = 'all', CacheInterface|string|null $cache = null, Closure|string|null $cacheKey = null, mixed ...$args)
 * @method Movement findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method Movement patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method array<Movement> patchEntities(iterable $entities, array $data, array $options = [])
 * @method Movement|false save(EntityInterface $entity, array $options = [])
 * @method Movement saveOrFail(EntityInterface $entity, array $options = [])
 * @method iterable<Movement>|ResultSetInterface<Movement>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<Movement>|ResultSetInterface<Movement> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<Movement>|ResultSetInterface<Movement>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<Movement>|ResultSetInterface<Movement> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin TimestampBehavior
 */
class MovementTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     */
    #[Override]
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('movement');
        $this->setDisplayField('comment');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id',
        ]);
        $this->belongsTo('Account', [
            'foreignKey' => 'account_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     */
    #[Override]
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('flags')
            ->notEmptyString('flags');

        $validator
            ->decimal('balance_value')
            ->requirePresence('balance_value', 'create')
            ->notEmptyString('balance_value');

        $validator
            ->scalar('comment')
            ->maxLength('comment', 100)
            ->requirePresence('comment', 'create')
            ->notEmptyString('comment');

        $validator
            ->integer('category_id')
            ->allowEmptyString('category_id');

        $validator
            ->integer('account_id')
            ->requirePresence('account_id', 'create')
            ->notEmptyString('account_id');

        return parent::validationDefault($validator);
    }

    /**
     * Overridden to automatically refresh the balance of the affected Account when a Movement is created or modified.
     *
     * @param Movement $entity
     */
    #[Override]
    protected function _onSaveSuccess(EntityInterface $entity, ArrayObject $options): bool
    {
        if (!parent::_onSaveSuccess($entity, $options)) {
            return false;
        }

        $entity->getAccount()->summarize();

        return true;
    }

    /**
     * Overridden to automatically refresh the balance of the affected Account when a Movement is deleted.
     *
     * @param Movement $entity
     */
    #[Override]
    public function delete(EntityInterface $entity, $options = []): bool
    {
        if (!parent::delete($entity, $options)) {
            return false;
        }

        $entity->getAccount()->summarize();

        return true;
    }


    public static function summarize(ResultSet $movements): float
    {
        $a = 0;

        foreach($movements as $movement) {
            $a += $movement->balance_value;
        }

        return round((float) $a, 2);
    }
}
