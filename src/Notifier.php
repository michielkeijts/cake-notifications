<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications;

use CakeNotifications\Model\Entity\Notification;
use CakeNotifications\Transport\TransportFactory;
use Cake\ORM\TableRegistry;
use Exception;
use Cake\Core\Configure;
use CakeNotifications\Transport\AbstractTransport;

class Notifier {
   
    /**
     * Sends a predefined notification
     * @param Notification $notification
     * @param array $options
     */
    public static function send(Notification $notification, array $options = [])
    {
        foreach ($notification->recipients as $transport=>$recipients) {
            $transport = static::getTransport(ucfirst($transport));
            
            if ($transport->getConfig('sendCombined')) {
                $transport->send($notification->message, $recipients, $notification);
                continue;
            } 
            
            foreach ($recipients as $recipient) {
                $transport->send($notification->message, [$recipient], $notification);
            }
        }
    }
    
    /**
     * Creates a notification
     * @param string $message
     * @param array $options
     * @return Notification
     */
    public static function create(string $message, array $options = []) : Notification
    {
        $notification = new Notification();
        
        $notification->message = $message;
        
        return $notification;        
    }
    
    /**
     * Get the Transport
     * @param string $transport Needs to be an ucfirst $transport
     * @return AbstractTransport
     */
    public static function getTransport(string $transport) : AbstractTransport
    {
        $config_key = sprintf('CakeNotifications.Transport.%s', $transport);
        $config = Configure::read($config_key, []);
        
        return TransportFactory::get($transport, $config);
    }
}