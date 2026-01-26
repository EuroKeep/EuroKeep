<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CategoryMapping Model
 *
 * @method \eurokeep\Model\Entity\CategoryMapping newEmptyEntity()
 * @method \eurokeep\Model\Entity\CategoryMapping newEntity(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\CategoryMapping[] newEntities(array $data, array $options = [])
 * @method \eurokeep\Model\Entity\CategoryMapping get($primaryKey, $options = [])
 * @method \eurokeep\Model\Entity\CategoryMapping findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \eurokeep\Model\Entity\CategoryMapping patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\CategoryMapping[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \eurokeep\Model\Entity\CategoryMapping|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \eurokeep\Model\Entity\CategoryMapping saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \eurokeep\Model\Entity\CategoryMapping[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\CategoryMapping[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\CategoryMapping[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \eurokeep\Model\Entity\CategoryMapping[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CategoryMappingTable extends Table
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

        $this->setTable('category_mapping');
        $this->setDisplayField('comment_mask');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('comment_mask')
            ->maxLength('comment_mask', 255)
            ->requirePresence('comment_mask', 'create')
            ->notEmptyString('comment_mask');

        $validator
            ->integer('category_id')
            ->allowEmptyString('category_id');

        return $validator;
    }

    public function mapComment(string $comment): array
    {
        return $this
            ->find()
            ->where(
                function ($exp) use ($comment) {
                    return $exp->add(sprintf("'%s' LIKE comment_mask", addslashes($comment)));
                }
            )
            ->firstOrFail()
        ->toArray();
    }
}
