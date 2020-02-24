<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Transport;

use CakeNotifications\Model\Entity\Notification;
use CakeNotifications\Transport\AbstractTransport;
use Cake\Mailer\Email;

/**
 * Description of SMSTransport
 *
 * @author michiel
 */
class EmailTransport extends AbstractTransport {
    
    /**
     * Abstract send method to send the notification to a single recipient
     * @param string $message 
     * @param string $to 
     * @param Notification $notification
     * @return bool
     */
    public function send(string $message, array $to, Notification $notification = null) : bool
    {
        $email = new Email($notification->config['email']);
        
        $email
            ->setTo($to)
            ->setEmailFormat('both')
            ->setSubject($notification->subject)
            ->viewBuilder()->setTemplate($notification->template);
        
        return !empty($email->send());
    }
}
