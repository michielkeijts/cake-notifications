<?php
/**
 * Configuration file for the Notifications Plugin. Or include this in app.php
 */
return [
	'Notifications' => [
        'Transport' => [
            'Slack' =>  [
                'senderFunction' => false // use built in client, define SLACK_WEBHOOK for this
            ],
            'Email' =>  [
                'senderFunction' => false // use built in client
            ],
            'Watsapp' =>  [
                'senderFunction' => false // not implemented
            ],
            'SMS' =>  [
                'senderFunction' => "MessagebirdSMSTransport::send"
            ]
        ],
	]
];
