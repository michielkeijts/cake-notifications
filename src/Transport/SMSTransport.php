<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Transport;

use CakeNotifications\Model\Entity\Notification;
use CakeNotifications\Transport\AbstractTransport;

/**
 * Description of SMSTransport
 *
 * @author michiel
 */
class SMSTransport extends AbstractTransport {
    
    /**
     * Abstract send method to send the notification to a single recipient
     * @param string $message 
     * @param string $to 
     * @param Notification $notification
     * @return bool
     */
    public function send(string $message, string $to, Notification $notification = null) : bool
    {
        $sender = $this->getConfig('senderFunction');
        
        if (!empty($sender)) {
            call_user_func_array($sender, compact('message', 'to', 'notification'));
        }
    }
}
