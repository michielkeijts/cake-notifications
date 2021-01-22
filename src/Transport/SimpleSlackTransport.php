<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Transport;

use Cake\Http\Client;
use CakeNotifications\Transport\AbstractTransport;
use CakeNotifications\Model\Entity\Notification;

/**
 * Description of SimpleSlackSender
 *
 * @author michiel
 */
class SimpleSlackTransport extends AbstractTransport {
    
    private $client;
    
    /**
     * Simple Slack uses a webhook
     * @param string $message
     * @param string $to
     * @param \CakeNotifications\Transport\SMSTransport\Notification $notification
     * @return boolean
     */
    public function send(string $message, array $to, Notification $notification = null) : bool
    {
        $response = $this->getClient()->post($this->getConfig('webhook'), json_encode(['text'=>$message]),['type'=>'json']);  
        
        return $response->getStatusCode()==200;
    }

    /**
     * Get a http client
     */
    private function getClient() : Client
    {
        if (! ($this->client instanceof Client)) {
            $this->client = new Client([
                'redirect'=>10,
                'ssl_verify_peer' => false,
                'ssl_verify_peer_name' => false,
                'ssl_verify_host' => false
            ]);
        }      
        
        return $this->client;
    }
}
