<?php

return [
    'emails' => [
        //fill this array with emails that will receive logs
    ],
//    'emails'    => explode(',',env('MAIL_LOGGER_EMAILS')),

    'from' => 'error@example.com',
//    'from'      => env('MAIL_LOGGER_FROM'),

    'from_name' => 'Error Mailer',
//    'from_name' => env('MAIL_LOGGER_FROM_NAME'),

    'subject' => 'Please check log message',
//    'subject'   => env('MAIL_LOGGER_SUBJECT'),

    'excludeRequestFields' => [],
];