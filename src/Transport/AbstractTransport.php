<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Transport;

use CakeNotifications\Model\Entity\Notification;
use Cake\Core\InstanceConfigTrait;

/**
 * Description of NotificationTransportInterface
 *
 * @author michiel
 */
abstract class AbstractTransport implements NotificationTransportInterface {
    use InstanceConfigTrait;
    
    /**
     * Default config for this class
     *
     *  'sendCombined' => TRUE
     * Some transports make it easier to send as one, for example, one sms.
     * Email is preferred to be send individually
     * 
     * @var array
     */
    protected $_defaultConfig = [
        'sendCombined' => FALSE
    ];

    /**
     * Constructor
     *
     * @param array $config Configuration options.
     */
    public function __construct($config = [])
    {
        $this->setConfig($config);
    }
    
    /**
     * Abstract send method to send the notification to a single recipient
     * @param string $message 
     * @param string $to 
     * @param Notification $notification
     * @return bool
     */
    public function send(string $message, array $to, Notification $notification = null) : bool
    {
                
    }
    
    /**
     * Get a sub directory class
     * 
     * e.g. SMSTransport\$nameSMSTransport
     * @param string $name
     * @return string
     */
    protected function getSubclassName(string $name) : string
    {
        $parts = explode("\\", get_called_class());
        $class = end($parts);
        
        return sprintf("%s\\%s%s",$class, $name, $class);
    }
}
