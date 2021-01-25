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
use CakeNotifications\Helper\NotificationTemplateParser;
use Cake\Utility\Inflector;

class Notifier {
   
    /**
     * Sends a predefined notification
     * @param Notification $notification
     */
    public static function send(Notification $notification)
    {
        static::getNotificationsTable()->save($notification);
        
        foreach ($notification->recipients as $transport=>$recipients) {
            $transport = static::getTransport(Inflector::camelize($transport));
            
            if ($transport->getConfig('sendCombined')) {
                $transport->send($notification->body, $recipients, $notification);
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
     * Create a Notification from template
     * @param string $template
     * @param array $viewVars
     * @param array $options
     * @return Notification
     */
    public static function createFromTemplate(string $template, array $viewVars = [], array $_options = []) : Notification
    {
        $template = new NotificationTemplateParser($template, $viewVars);
        
        $options = $template->getOptions($_options);
        $options['recipients'] = static::enrichRecipients($options['recipients']);
        
        $notification = static::create($template->getMessage(), $options);
        
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
     *  username2.slack.user.profile.phone => $transport,
     *  %ALL_USERS%.id => $transport (Special variable)
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
            
            if ($username == '%ALL_USERS%') {
                $users = $Users->find();
            } else {
                if (empty($lastUser) || strtolower($lastUser->username) != strtolower($username)) {
                    if (is_numeric($username)) {
                        $lastUser = $Users->get($username);
                    } else {
                        $lastUser = $Users->findByUsername($username)->firstOrFail();
                    }
                }
                
                $users = [$lastUser];
            }
            
            foreach ($users as $user) {
                $key = $user->get($property);
                if (count($paths) > 2) {
                    $data = $user->get($property);
                    if (!is_array($data)) {
                        continue;
                    }
                    $key = Hash::get($user->get($property), implode('.', $paths));
                }
                $recipients[$key] = $transport;
            }
        }
        
        return $recipients;
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
     *  username2.slack.user.profile.phone => $transport,
     *  %ALL_USERS%.id => $transport (Special variable)
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
    public static function enrichRecipients(array $simpleRecipients) : array
    {
        $recipients = [];
        
        foreach ($simpleRecipients as $receiver_user => $transports) {
            if (!is_array($transports)) {
                $transports = [$transports];
            }
            
            $users = static::getUser($receiver_user);
            
            foreach ($transports as $transport) {
                $config = Configure::read('CakeNotifications.Transport.'.Inflector::camelize($transport));
                
                if (empty($users) || !isset($config['UserAddressProperty']) || empty($config['UserAddressProperty'])) {
                    if (!isset($recipients[$receiver_user]) || !is_array($recipients[$receiver_user])) {
                        $recipients[$receiver_user]=[];
                    }
                    array_push($recipients[$receiver_user], $transport);
                    continue;
                }
                
                foreach ($users as $user) {
                    $property_parts = explode('.', $config['UserAddressProperty']);
                    $property = array_shift($property_parts);
                    $key = $user->get($property);
                    if (count($property_parts) > 1) {
                        if (!is_array($data)) {
                            continue;
                        }
                        $key = Hash::get($key, implode('.', $property_parts));
                    }
                    
                    if (!isset($recipients[$key]) || !is_array($recipients[$key])) {
                        $recipients[$key]=[];
                    }
                    array_push($recipients[$key], $transport);
                }
            }
        }
        
        return $recipients;
    }
    
    /**
     * Get the User by id/username identified by $field
     * @param mixed $field
     * @return array
     */
    public static function getUser($field) : array
    {
        $Users = TableRegistry::getTableLocator()->get('Users');
        
        if ($field == '%ALL_USERS%') {
            return $Users->find()->toArray();
        } 
        
        if (is_numeric($field)) {
            return $Users->findById($field)->toArray();
        }
        
        return $Users->findByUsername($field)->toArray();
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