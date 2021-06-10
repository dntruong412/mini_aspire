<?php

$oneDaySeconds = 24 * 60 * 60;

return [
    'user' => [
        'prefix'                 => 'user',
        'token_duration'         => $oneDaySeconds * 30, // 1 month
        'token_name'             => 'token',
        'refresh_token_duration' => 30 * $oneDaySeconds,
        'refresh_token_name'     => 'refresh_token'
    ]
];