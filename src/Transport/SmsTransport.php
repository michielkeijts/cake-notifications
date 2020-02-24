<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Transport;

use CakeNotifications\Model\Entity\Notification;
use CakeNotifications\Transport\AbstractTransport;
use CakeNotifications\Transport\TransportFactory;
use Cake\Core\Configure;

/**
 * Description of SMSTransport
 *
 * @author michiel
 */
class SmsTransport extends AbstractTransport {
    
    protected $_defaultConfig = [
        'sendCombined' => FALSE
    ];
    
    /**
     * Abstract send method to send the notification to a single recipient
     * @param string $message 
     * @param string $to 
     * @param Notification $notification
     * @return bool
     */
    public function send(string $message, array $to, Notification $notification = null) : bool
    {
        $transporterConfig = Configure::read('CakeNotifications.Transport.Sms.' . $notification->config['sms']);
        
        if ($transporterConfig['provider']) {
            $transporter = TransportFactory::get($transporterConfig['provider'] . 'Sms', $transporterConfig);
            
            return $transporter->send($message, $to, $notification);
        } 
        
        return false;
    }

}
