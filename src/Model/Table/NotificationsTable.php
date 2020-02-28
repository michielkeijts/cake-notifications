<?php
namespace CakeNotifications\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use CakeNotifications\Model\Entity\Notification;
use Cake\Database\Schema\TableSchema;

/**
 * Notifications Model
 *
 * @property \App\Model\Table\SitesTable&\Cake\ORM\Association\BelongsTo $Sites
 *
 * @method \App\Model\Entity\Notification get($primaryKey, $options = [])
 * @method \App\Model\Entity\Notification newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Notification[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Notification|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Notification saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Notification patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Notification[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Notification findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NotificationsTable extends Table
{
    public function _initializeSchema(TableSchema $schema) {
        $schema = parent::_initializeSchema($schema);
        
        $schema->setColumnType('config',  'json');
        $schema->setColumnType('recipients',  'json');
        
        return $schema;
    }
    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('notifications');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Sites', [
            'foreignKey' => 'site_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('language')
            ->maxLength('language', 10)
            ->allowEmptyString('language');

        $validator
            ->scalar('name')
            ->maxLength('name', 150)
            ->allowEmptyString('name');

        $validator
            ->scalar('body')
            ->maxLength('body', 255)
            ->requirePresence('body', 'create')
            ->notEmptyString('body');

        $validator
            ->scalar('recipients')
            ->maxLength('recipients', 16777215)
            ->allowEmptyString('recipients');

        $validator
            ->dateTime('pushed')
            ->allowEmptyDateTime('pushed');

        $validator
            ->scalar('template')
            ->maxLength('template', 150)
            ->allowEmptyString('template');

        $validator
            ->scalar('layout')
            ->maxLength('layout', 150)
            ->allowEmptyString('layout');

        $validator
            ->integer('created_by')
            ->allowEmptyString('created_by');

        $validator
            ->integer('modified_by')
            ->allowEmptyString('modified_by');

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        $validator
            ->integer('deleted_by')
            ->allowEmptyString('deleted_by');

        return $validator;
    }
    
    /**
     * Creates a notification
     * $options[
     *  'name' => subject of notification
     *  'recipients' => list of recipients (address=>transport)
     * ];
     * 
     * @param string $message
     * @param array $options
     * @return Notification
     */
    public function create(string $message, array $options = []) : Notification
    {
        $options = $options + [
            'name'          => 'New Notification',
            'recipients'    => []
        ];
        
        $notification = new Notification();
        
        $notification->body = $message;
        $notification->name = $options['name'];
        
        if (is_array($options['recipients'])) {
            foreach ($options['recipients'] as $address=>$transport) {
                $notification->addRecipient($address, $transport);
            }
        }
        
        return $this->save($notification);        
    }
}
