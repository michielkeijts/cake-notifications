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
use Cake\Utility\Hash;
use CakeNotifications\Model\Table\NotificationsTable;

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
               $transport->send($notification->body, [$recipient], $notification);
            }
        }
        
        $notification->sent = time();
        
        static::getNotificationsTable()->save($notification);
    }
    
    /**
     * Creates a notification
     * @param string $message
     * @param array $options
     * @return Notification
     */
    public static function create(string $message, array $options = []) : Notification
    {
        $notification = static::getNotificationsTable()->create($message, $options);
                
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
    
    /**
     * Get a filled user property => $transport array.
     * 
     * Makes it easy to create a username list for notifications
     * 
     * For example:
     * 
     * [ 
     *  username1.id => $transport,
     *  username2.slack.user.profile.phone => $transport
     * ]
     * 
     * will return as
     * 
     * [ 
     *  1 => $transport,
     *  +3112345 => $transport
     * ]
     * @param array $simpleRecipients
     * @return array
     */
    public static function fillUserInfoFor(array $simpleRecipients) : array
    {
        $Users = TableRegistry::getTableLocator()->get('Users');
        $lastUser = null;
        $recipients = [];
        
        foreach ($simpleRecipients as $userpath => $transport) {
            $paths = explode('.', $userpath);
            $username = array_shift($paths);
            $property = array_shift($paths);
            
            if (empty($lastUser) || strtolower($lastUser->username) != strtolower($username)) {
                if (is_numeric($username)) {
                    $lastUser = $Users->get($username);
                } else {
                    $lastUser = $Users->findByUsername($username)->firstOrFail();
                }
            }
            
            $key = $lastUser->get($property);
            if (count($paths) > 2) {
                $data = $lastUser->get($property);
                if (!is_array($data)) {
                    continue;
                }
                $key = Hash::get($lastUser->get($property), implode('.', $paths));
            }
            $recipients[$key] = $transport;
        }
        
        return $recipients;
    }
    
    /**
     * 
     * @return NotificationsTable
     */
    public static function getNotificationsTable() : NotificationsTable
    {
        return TableRegistry::getTableLocator()->get('CakeNotifications.Notifications');
    }
}