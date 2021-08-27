<?php

return [
    'channel' => [
        'id' => env('LINE_CHANNEL_ID', null),
        'access_token' => env('LINE_CHANNEL_ACCESS_TOKEN', null),
        'secret' => env('LINE_CHANNEL_SECRET', null),
    ],
];
