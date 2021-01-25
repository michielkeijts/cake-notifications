<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Transport;

use CakeNotifications\Model\Entity\Notification;
use CakeNotifications\Transport\AbstractTransport;
use Cake\Mailer\Mailer;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;

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
        $mailer = new Mailer('default');
        
        $mailer
                ->addTo($to)
                ->setEmailFormat('both')
                ->setSubject($notification->name)
                ->setViewVars('content', nl2br($message))
                ->viewBuilder()
                ->setTemplate($notification->template);
        
        if (Plugin::isLoaded('Queue')) {
            return $this->sendAsQueuedJob($mailer);
        }
        
        return !empty($mailer->send());
    }
    
    /**
     * Send as a Queued Job instead of direct sending
     * @param Mailer $mailer
     * @return bool
     */
    public function sendAsQueuedJob(Mailer $mailer) : bool
    {
        TableRegistry::getTableLocator()->get('Queue.QueuedJobs')->createJob(
            'Email',
            ['settings' => $mailer],
            ['group' => 'email']
        );
        
        return TRUE;
    }
}
