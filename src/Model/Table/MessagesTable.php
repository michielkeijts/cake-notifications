<?php
namespace CakeNotifications\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Database\Schema\TableSchemaInterface;

/**
 * Messages Model
 *
 * @method \CakeNotifications\Model\Entity\Message get($primaryKey, $options = [])
 * @method \CakeNotifications\Model\Entity\Message newEntity($data = null, array $options = [])
 * @method \CakeNotifications\Model\Entity\Message[] newEntities(array $data, array $options = [])
 * @method \CakeNotifications\Model\Entity\Message|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CakeNotifications\Model\Entity\Message saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CakeNotifications\Model\Entity\Message patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \CakeNotifications\Model\Entity\Message[] patchEntities($entities, array $data, array $options = [])
 * @method \CakeNotifications\Model\Entity\Message findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MessagesTable extends Table
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

        $this->setTable('messages');
        $this->setDisplayField('name');
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 150);

        $validator
            ->scalar('body')
            ->requirePresence('body', 'create');

        $validator
            ->integer('created_by')
            ->allowEmptyString('created_by');

        $validator
            ->integer('modified_by')
            ->allowEmptyString('modified_by');

        return $validator;
    }
}
