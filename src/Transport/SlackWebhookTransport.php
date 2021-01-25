<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Transport;

use CakeNotifications\Model\Entity\Notification;
use CakeNotifications\Transport\AbstractTransport;
use Cake\Core\Configure;
use CakeNotifications\Transport\Provider\ProviderFactory;

/**
 * Description of SMSTransport
 *
 * @author michiel
 */
class SlackWebhookTransport extends AbstractTransport {
    
    /**
     * Abstract send method to send the notification to a single recipient
     * @param string $message 
     * @param string $to 
     * @param Notification $notification
     * @return bool
     */
    public function send(string $message, array $to, Notification $notification = null) : bool
    {
        $transporterConfig = Configure::read('CakeNotifications.Transport.SlackWebhook.' . $notification->config['slack']);
        
        if ($transporterConfig['provider']) {
            $provider = ProviderFactory::get('Slack'.$transporterConfig['provider'], $transporterConfig);
            
            return $provider->send($message, $to, $notification);
        } 
        
        return false;
    } 
}
