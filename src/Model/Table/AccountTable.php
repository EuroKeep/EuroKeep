<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Account Model
 *
 * @property \eurokeep\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \eurokeep\Model\Table\MovementTable&\Cake\ORM\Association\HasMany $Movement
 *
 * @method \eurokeep\Model\Entity\Account newEmptyEntity()
 * @method \eurokeep\Model\Entity\Account newEntity(array $data, array $options = [])
 * @method array<\eurokeep\Model\Entity\Account> newEntities(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Account get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \eurokeep\Model\Entity\Account findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \eurokeep\Model\Entity\Account patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\eurokeep\Model\Entity\Account> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Account|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \eurokeep\Model\Entity\Account saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Account>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Account>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Account>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Account> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Account>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Account>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Account>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Account> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccountTable extends Table
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

        $this->setTable('account');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Accounttype', [
            'foreignKey' => 'accounttype_id',
        ]);

        $this->hasMany('Movement', [
            'foreignKey' => 'account_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
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
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('emoji')
            ->maxLength('emoji', 8)
            ->requirePresence('emoji', 'create')
            ->notEmptyString('emoji');

        $validator
            ->scalar('iban')
            ->maxLength('iban', 50)
            ->allowEmptyString('iban');

        $validator
            ->decimal('balance_value')
            ->requirePresence('balance_value', 'create')
            ->notEmptyString('balance_value');

        $validator
            ->scalar('balance_currency')
            ->maxLength('balance_currency', 100)
            ->requirePresence('balance_currency', 'create')
            ->notEmptyString('balance_currency');

        $validator
            ->integer('accounttype_id')
            ->requirePresence('accounttype_id', 'create')
            ->notEmptyString('accounttype_id');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
