<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Transport;

use CakeNotifications\Model\Entity\Notification;
use CakeNotifications\Transport\AbstractTransport;
use CakeNotifications\Transport\Provider\ProviderFactory;
use Cake\Core\Configure;

/**
 * Description of SMSTransport
 *
 * @author michiel
 */
class SlackTransport extends AbstractTransport {
    
    /**
     * Abstract send method to send the notification to a single recipient
     * @param string $message 
     * @param string $to 
     * @param Notification $notification
     * @return bool
     */
    public function send(string $message, array $to, Notification $notification = null) : bool
    {
        $transporterConfig = Configure::read('CakeNotifications.Transport.Slack.' . $notification->config['slack']);
        
        if ($transporterConfig['provider']) {
            $provider = ProviderFactory::get('Slack'.$transporterConfig['provider'], $transporterConfig);
            
            // in case multiple $to, send individually, but argument needs to be array
            foreach ($to as $current_recipient) {
                return $provider->send($message, [$current_recipient], $notification);
            }
        } 
        
        return false;
    } 
}
