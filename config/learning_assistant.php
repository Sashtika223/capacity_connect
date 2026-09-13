<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Learning Assistant Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the settings for the AI Learning Assistant feature.
    | To ensure "No Fake AI" is used, the service will refuse to operate
    | unless a valid API key is provided here.
    |
    */

    'provider' => env('AI_ASSISTANT_PROVIDER', 'openai'),

    'api_key' => env('AI_ASSISTANT_API_KEY', null),

    'model' => env('AI_ASSISTANT_MODEL', 'gpt-4'),
];
