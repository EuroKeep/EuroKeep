<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Category Model
 *
 * @property \eurokeep\Model\Table\MovementTable&\Cake\ORM\Association\HasMany $Movement
 *
 * @method \eurokeep\Model\Entity\Category newEmptyEntity()
 * @method \eurokeep\Model\Entity\Category newEntity(array $data, array $options = [])
 * @method array<\eurokeep\Model\Entity\Category> newEntities(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Category get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \eurokeep\Model\Entity\Category findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \eurokeep\Model\Entity\Category patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\eurokeep\Model\Entity\Category> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Category|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \eurokeep\Model\Entity\Category saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Category>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Category>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Category>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Category> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Category>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Category>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Category>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Category> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CategoryTable extends Table
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

        $this->setTable('category');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Movement', [
            'foreignKey' => 'category_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
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
            ->scalar('emoji')
            ->maxLength('emoji', 16)
            ->requirePresence('emoji', 'create')
            ->notEmptyString('emoji');

        $validator
            ->scalar('name')
            ->maxLength('name', 128)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('parent_category_id')
            ->allowEmptyString('parent_category_id');

        return $validator;
    }
}
