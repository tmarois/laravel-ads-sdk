<?php

return [

    'developerToken' => env('BING_DEVELOPER_TOKEN', ''),

    'clientId' => env('BING_CLIENT_ID', ''),

    'clientSecret' => env('BING_CLIENT_SECRET', ''),

    'refreshToken' => env('BING_REFRESH_TOKEN', ''),

    'redirect_uri' => 'https://login.microsoftonline.com/common/oauth2/nativeclient'
];
