<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace CakeNotifications\Helper;

use Cake\Http\Client;

/**
 * Description of SimpleSlackSender
 *
 * @author michiel
 */
class SimpleSlackSender {
    
    private static $client;
    
    public static function send($message, $webhook="")
    {
        static::getClient()->post(env('SLACK_WEBHOOK',""), json_encode(['text'=>$message]),['type'=>'json']);  
    }

    /**
     * Get a http client
     */
    private static function getClient() : Client
    {
        if (! (static::$client instanceof Client)) {
            static::$client = new Client([
                'redirect'=>10,
                'ssl_verify_peer' => false,
                'ssl_verify_peer_name' => false,
                'ssl_verify_host' => false
            ]);
        }      
        
        return static::$client;
    }
}
