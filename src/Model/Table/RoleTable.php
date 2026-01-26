<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Role Model
 *
 * @property \eurokeep\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 *
 * @method \eurokeep\Model\Entity\Role newEmptyEntity()
 * @method \eurokeep\Model\Entity\Role newEntity(array $data, array $options = [])
 * @method array<\eurokeep\Model\Entity\Role> newEntities(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Role get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \eurokeep\Model\Entity\Role findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \eurokeep\Model\Entity\Role patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\eurokeep\Model\Entity\Role> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\Role|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \eurokeep\Model\Entity\Role saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Role>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Role>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Role>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Role> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Role>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Role>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\eurokeep\Model\Entity\Role>|\Cake\Datasource\ResultSetInterface<\eurokeep\Model\Entity\Role> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RoleTable extends Table
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
