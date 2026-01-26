<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Account Model
 *
 * @method \eurokeep\Model\Entity\Account newEmptyEntity()
 * @method \eurokeep\Model\Entity\Account newEntity(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Account[] newEntities(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Account get($primaryKey, $options = [])
 * @method \eurokeep\Model\Entity\Account findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \eurokeep\Model\Entity\Account patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Account[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Account|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \eurokeep\Model\Entity\Account saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \eurokeep\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccountTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
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
            ->requirePresence('iban', 'create')
            ->notEmptyString('iban');

        $validator
            ->numeric('balance_value')
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
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        return $validator;
    }
}
