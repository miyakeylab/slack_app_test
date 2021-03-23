<?php

return [
    'emoji_change' => env('SLACK_NOTIFICATION_WEBHOOK'),
    'setting' => [
        'api_key' => env('SLACK_API_TOKEN'),
        'team_id' => env('SLACK_TEAM_ID'),
        'app_id' => env('SLACK_API_APP_ID'),
        'access_token' => env('SLACK_ACCESS_TOKEN'),
        'oauth_token' => env('SLACK_OAUTH_TOKEN'),
    ],
];
