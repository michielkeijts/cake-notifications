<?php
namespace CakeNotifications\Model\Entity;

use Cake\ORM\Entity;
use Exception;

/**
 * Message Entity
 *
 * @property int $id
 * @property string|null $group_id
 * @property string|null $name
 * @property string $body
 * @property boolean $message_read

 * @property \Cake\I18n\FrozenTime|null $created
 * @property int|null $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 */
class Message extends Entity
{
   /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'group_id' => true,
        'message_read' => true,
        'body' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
    ];
}
