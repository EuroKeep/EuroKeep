<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Role Model
 *
 * @property \eurokeep\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 *
 * @method \eurokeep\Model\Entity\Role newEmptyEntity()
 * @method \eurokeep\Model\Entity\Role newEntity(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Role[] newEntities(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Role get($primaryKey, $options = [])
 * @method \eurokeep\Model\Entity\Role findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \eurokeep\Model\Entity\Role patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Role[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Role|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \eurokeep\Model\Entity\Role saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \eurokeep\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\Role[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RoleTable extends Table
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

        $this->setTable('role');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Users', [
            'foreignKey' => 'role_id',
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
