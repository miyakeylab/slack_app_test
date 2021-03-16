<?php

return [
    'emoji_change' => [
        env('SLACK_NOTIFICATION_WEBHOOK'),
    ],
    'setting' => [
        'api_key' => [
            env('SLACK_API_TOKEN'),
        ],
        'team_id' => [
            env('SLACK_TEAM_ID'),
        ],
        'app_id' => [
            env('SLACK_API_APP_ID'),
        ],
    ],
];
