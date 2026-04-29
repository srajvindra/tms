<?php

return [
    'name' => 'YouTube',

    'client_id'     => env('YOUTUBE_CLIENT_ID', ''),
    'client_secret' => env('YOUTUBE_CLIENT_SECRET', ''),
    'refresh_token' => env('YOUTUBE_REFRESH_TOKEN', ''),
    'redirect_uri'  => env('YOUTUBE_REDIRECT_URI', 'urn:ietf:wg:oauth:2.0:oob'),
];
