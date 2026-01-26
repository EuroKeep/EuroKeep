<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Accounttype Model
 *
 * @property \eurokeep\Model\Table\AccountTable&\Cake\ORM\Association\HasMany $Account
 *
 * @method \eurokeep\Model\Entity\Accounttype newEmptyEntity()
 * @method \eurokeep\Model\Entity\Accounttype newEntity(array $data, array $options = [])
 * @method array<\eurokeep\Model\Entity\Accounttype> newEntities(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Accounttype get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \eurokeep\Model\Entity\Accounttype findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \eurokeep\Model\Entity\Accounttype patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\eurokeep\Model\Entity\Accounttype> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Accounttype|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \eurokeep\Model\Entity\Accounttype saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Accounttype>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Accounttype>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Accounttype>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Accounttype> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Accounttype>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Accounttype>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Accounttype>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Accounttype> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccounttypeTable extends Table
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

        $this->setTable('accounttype');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Account', [
            'foreignKey' => 'accounttype_id',
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
            ->scalar('name')
            ->maxLength('name', 128)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }
}
