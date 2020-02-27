<?php
namespace CakeNotifications\Model\Entity;

use Cake\ORM\Entity;
use CakeNotifications\Transport\SMSTransport;
use CakeNotifications\Transport\SlackTransport;
use CakeNotifications\Transport\EmailTransport;
use CakeNotifications\Transport\WatsappTransport;
use Exception;

/**
 * Notification Entity
 *
 * @property int $id
 * @property int|null $site_id
 * @property string|null $language
 * @property string|null $name
 * @property string $body
 * @property string|null $recipients
 * @property \Cake\I18n\FrozenTime|null $pushed
 * @property string|null $template
 * @property string|null $layout
 * @property \Cake\I18n\FrozenTime|null $created
 * @property int|null $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 * @property \Cake\I18n\FrozenTime|null $deleted
 * @property int|null $deleted_by
 *
 * @property \App\Model\Entity\Site $site
 */
class Notification extends Entity
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
        'site_id' => true,
        'language' => true,
        'name' => true,
        'body' => true,
        'recipients' => true,
        'sent' => true,
        'template' => true,
        'layout' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'deleted' => true,
        'deleted_by' => true,
        'site' => true,
    ];
    
    /**
     * Create with default options
     * @param array $properties
     * @param array $options
     * @return type
     */
    public function __construct(array $properties = array(), array $options = array()) {
        $properties = $properties + [
            "recipients" => [],
            "message" => "",
            "template" => "default",
            "layout" => "default",
            "config" => [
                'email' => 'default',
                'sms' => 'default',
                'whatsapp' => 'default',
                'slack' => 'default'
            ],
            "subject" => "Subject Of Message"
        ];
        
        return parent::__construct($properties, $options);
    }
    
    /**
     * $transport is full classname, or 'SMS','Email','Slack','WhatsApp
     * @param string $address
     * @param string $transport
     */
    public function addRecipient(string $address, string $transport)
    {
        $class = ucfirst($transport);
        
        if (!is_array($this->recipients)) { 
            $this->recipients = [];
        }
        
        if (!isset($this->recipients[$transport])) {
            $this->recipients[$transport] = [];
        }
        
        array_push($this->recipients[$transport], $address);
    }
}
