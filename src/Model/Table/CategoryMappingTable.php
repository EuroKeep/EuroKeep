<?php
declare(strict_types=1);

namespace eurokeep\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use eurokeep\Model\Entity\CategoryMapping;
use Override;

/**
 * CategoryMapping Model
 *
 * @method CategoryMapping newEmptyEntity()
 * @method CategoryMapping newEntity(array $data, array $options = [])
 * @method CategoryMapping[] newEntities(array $data, array $options = [])
 * @method CategoryMapping get($primaryKey, $options = [])
 * @method CategoryMapping findOrCreate($search, ?callable $callback = null, $options = [])
 * @method CategoryMapping patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method CategoryMapping[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method CategoryMapping|false save(EntityInterface $entity, $options = [])
 * @method CategoryMapping saveOrFail(EntityInterface $entity, $options = [])
 * @method CategoryMapping[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method CategoryMapping[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method CategoryMapping[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method CategoryMapping[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin TimestampBehavior
 */
class CategoryMappingTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     */
    #[Override]
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
     * @param Validator $validator Validator instance.
     */
    #[Override]
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

        return parent::validationDefault($validator);
    }

    public function mapComment(string $comment): array
    {
        return $this
            ->find()
            ->where(
                fn($exp) => $exp->add(sprintf("'%s' LIKE comment_mask", addslashes($comment)))
            )
            ->firstOrFail()
        ->toArray();
    }
}
