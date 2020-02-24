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
 * In order to have a compatibility to be saved. 
 *
 * @property string $message
 * @property array $recipients
 */
class Notification extends Entity
{
    public $recipients = [];
    public $message = "";
    public $template = "default";
    public $layout = "default";
    public $options = [
        'email_config' => 'default'
    ];
    public $subject = "Email Subject";
    
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
        '*'
    ];
    
    /**
     * $transport is full classname, or 'SMS','Email','Slack','WhatsApp
     * @param string $address
     * @param string $transport
     */
    public function addRecipient(string $address, string $transport)
    {
        $class = ucfirst($transport);
        
        $this->recipients[] = ['address'=>$address, 'transport'=>$class];
    }
}
