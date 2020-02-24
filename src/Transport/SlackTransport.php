<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Transport;

use CakeNotifications\Model\Entity\Notification;
use CakeNotifications\Transport\AbstractTransport;
use CakeNotifications\Helper\SimpleSlackSender;

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
    public function send(string $message, string $to, Notification $notification = null) : bool
    {
        $slackclient = $this->getConfig('senderFunction');
        
        if (!empty($slackclient)) {
            call_user_func_array($slackclient, compact('message', 'to', 'notification'));
        }
        
        SimpleSlackSender::send($message);
        
        return true;
    } 
}
