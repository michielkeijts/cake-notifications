<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Transport\Provider;

use Cake\Http\Client;
use CakeNotifications\Transport\AbstractTransport;
use CakeNotifications\Model\Entity\Notification;
use CakeNotifications\Client\Auth\Bearer;

/**
 * Description of SimpleSlackSender
 *
 * @author michiel
 */
class SlackBotProvider extends AbstractTransport {
    
    private $client;
    
    /**
     * Method being used to send direct message
     * @var string 
     */
    private static $SLACK_METHOD = 'https://slack.com/api/chat.postMessage';
    
    /**
     * Slack Bot uses a Bot Oauth Token
     * @param string $message
     * @param array $to
     * @param \CakeNotifications\Transport\SMSTransport\Notification $notification
     * @return boolean
     */
    public function send(string $message, array $to, Notification $notification = null) : bool
    {
        $response = $this->getClient()->post(
                static::$SLACK_METHOD, 
                json_encode([
                    'channel' => reset($to),
                    'text' => $message, 
                    'as_user' => $this->getConfig('as_user', TRUE)
                ]),
                [
                    'type'=>'json', 
                    'auth' => [
                        'type' => Bearer::class,
                        'token' => $this->getConfig('auth')
                    ]
                ]);  
        
        
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
