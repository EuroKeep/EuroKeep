<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use eurokeep\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * User Model
 *
 * @property \eurokeep\Model\Table\AccountTable&\Cake\ORM\Association\HasMany $Account
 * @property \eurokeep\Model\Table\MovementTable&\Cake\ORM\Association\HasMany $Movement
 *
 * @method \eurokeep\Model\Entity\User newEmptyEntity()
 * @method \eurokeep\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\User get($primaryKey, $options = [])
 * @method \eurokeep\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \eurokeep\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \eurokeep\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \eurokeep\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Role', [
            'foreignKey' => 'role_id',
        ]);
        $this->hasMany('Account', [
            'foreignKey' => 'user_id',
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

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->numeric('password')
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->numeric('role_id')
            ->requirePresence('role_id', 'role_id')
            ->notEmptyString('role_id');

        return $validator;
    }

    public function create(string $email, string $name, string $password) : User
    {
        $user = $this->newEmptyEntity();

        $user->email = $email;
        $user->name = $name;
        $user->password = $password;

        $this->save($user);

        return $user;
    }

    public function setPassword(User $user, string $password) : void
    {
        // VERIFY COMPLEXITY
        $user->password = $password;
        $user->flags = 3; //Make this the binary operation!
        $this->save($user);
    }
}
