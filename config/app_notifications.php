<?php
/**
 * Configuration file for the Notifications Plugin. Or include this in app.php
 */
return [
	'CakeNotifications' => [
        'Transport' => [
            'Slack' =>  [
                'default' => [
                    'provider' => "Simple",
                    'webhook' => env('SLACK_WEBHOOK',"")
                ]
            ],
            'Email' =>  [
                'senderFunction' => false // use built in client
            ],
            'Watsapp' =>  [
                'senderFunction' => false // not implemented
            ],
            'Sms' =>  [
                'default' => [
                    'provider' => "Messagebird",
                    'originator' => 'MessageBird', 
                    'key' => env('MESSAGEBIRD_API_KEY', FALSE)
                ]
            ]
        ],
	]
];
