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
   
    public static function send(Notification $notification, array $options = [])
    {
        foreach ($notification->recipients as $recipient) {
            $transport = static::getTransport($recipient['transport']);
            
            $transport->send($notification->message, $recipient['address'], $notification);
        }
    }
    
    public static function create(string $message, array $options = []) : Notification
    {
        $notification = new Notification([
            'message'=>$message
        ]);
        
        return $notification;        
    }
    
    /**
     * Abstract T
     */
    public static function getTransport(string $transport) : AbstractTransport
    {
        $config_key = sprintf('Notifications.Transport.%s', $transport);
        $config = Configure::read($config_key, []);
        
        return TransportFactory::get($transport, $config);
    }
}