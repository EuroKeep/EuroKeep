<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use eurokeep\Model\Entity\Movement;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Movement Model
 *
 * @method \eurokeep\Model\Entity\Movement newEmptyEntity()
 * @method \eurokeep\Model\Entity\Movement newEntity(array $data, array $options = [])
 * @method array<\eurokeep\Model\Entity\Movement> newEntities(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Movement get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \eurokeep\Model\Entity\Movement findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \eurokeep\Model\Entity\Movement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\eurokeep\Model\Entity\Movement> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Movement|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \eurokeep\Model\Entity\Movement saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Movement>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Movement>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Movement>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Movement> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Movement>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Movement>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Movement>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Movement> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MovementTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('movement');
        $this->setDisplayField('comment');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->belongsTo('Category', [
            'foreignKey' => 'category_id',
        ]);
        $this->belongsTo('Account', [
            'foreignKey' => 'account_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
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

        return $validator;
    }

    /**
     * Overridden to automatically refresh the balance of the affected Account when a Movement is created or modified.
     *
     * @param Movement $entity
     * @param ArrayObject $options
     * @return bool
     */
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
     * @param $options
     * @return bool
     */
    public function delete(EntityInterface $entity, $options = []): bool
    {
        if (!parent::delete($entity, $options)) {
            return false;
        }

        $entity->getAccount()->summarize();

        return true;
    }
}
