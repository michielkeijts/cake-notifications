<?php
/**
 * Configuration file for the Notifications Plugin. Or include this in app.php
 */
return [
	'CakeNotifications' => [
        'Transport' => [
            'Slack' =>  [
                'default' => [
                    'provider' => "Bot",
                    'auth' => env('SLACK_BOT_TOKEN',""),
                    'as_user' => TRUE //default
                ],
                'UserAddressProperty' => 'slack_id'
            ],
            'SlackWebhook' =>  [
                'default' => [
                    'provider' => "Webook",
                    'webhook' => env('SLACK_WEBHOOK',"")
                ]
            ],
            'Email' =>  [
                'senderFunction' => false, // use built in client,
                'UserAddressProperty' => 'email'
            ],
            'Watsapp' =>  [
                'senderFunction' => false, // not implemented
                'UserAddressProperty' => 'phone'
            ],
            'Sms' =>  [
                'default' => [
                    'provider' => "MessageBird",
                    'originator' => 'BlogicMedia', 
                    'key' => env('MESSAGEBIRD_API_KEY', FALSE)
                ],
                'UserAddressProperty' => 'phone'
            ]
        ],
	]
];