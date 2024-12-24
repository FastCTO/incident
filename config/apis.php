<?php

return [
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'endpoint' => 'https://api.openai.com/v1/chat/completions',
        'model' => 'gpt-3.5-turbo', // Use 'gpt-4' if preferred
        'max_tokens' => 150,
    ],
];

