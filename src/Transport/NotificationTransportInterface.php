<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */


namespace CakeNotifications\Transport;

use CakeNotifications\Model\Entity\Notification;

/**
 * Description of NotificationTransportInterface
 *
 * @author michiel
 */
interface NotificationTransportInterface {

    public function send(string $message, string $to, Notification $notification = null) : bool;
    
}
