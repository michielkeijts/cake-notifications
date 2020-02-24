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
class WhatsAppTransport extends AbstractTransport {
    
    /**
     * Abstract send method to send the notification
     * @param Notification $notification
     * @param string $to 
     * @return bool
     */
    public function send(Notification $notification, $to) : bool
    {
        throw new \Cake\Http\Exception\NotImplementedException("Sorry. Not yet implemented");
    } 
}
