<?php

return [
    /*
    |--------------------------------------------------------------------------
    | LM Studio Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for your LM Studio local server. Default is localhost:1234
    | Make sure LM Studio is running and the local server is enabled.
    |
    */
    'base_url' => env('LMSTUDIO_BASE_URL', 'http://localhost:1234/v1'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Maximum time (in seconds) to wait for a response from LM Studio.
    | Local models can be slower, so a higher timeout is recommended.
    |
    */
    'timeout' => env('LMSTUDIO_TIMEOUT', 120),

    /*
    |--------------------------------------------------------------------------
    | Default Model
    |--------------------------------------------------------------------------
    |
    | The default model to use when none is specified. This should match
    | a model you have downloaded in LM Studio.
    |
    */
    'default_model' => env('LMSTUDIO_DEFAULT_MODEL', 'llama-3.2-3b-instruct'),

    /*
    |--------------------------------------------------------------------------
    | Default Parameters
    |--------------------------------------------------------------------------
    |
    | Default parameters for chat completions. These can be overridden
    | per request.
    |
    */
    'defaults' => [
        'max_tokens' => env('LMSTUDIO_MAX_TOKENS', 2048),
        'temperature' => env('LMSTUDIO_TEMPERATURE', 0.7),
        'top_p' => env('LMSTUDIO_TOP_P', 0.9),
        'frequency_penalty' => env('LMSTUDIO_FREQUENCY_PENALTY', 0.0),
        'presence_penalty' => env('LMSTUDIO_PRESENCE_PENALTY', 0.0),
    ],

    /*
    |--------------------------------------------------------------------------
    | Streaming
    |--------------------------------------------------------------------------
    |
    | Enable or disable streaming support. Streaming allows you to receive
    | tokens as they are generated.
    |
    */
    'streaming' => [
        'enabled' => env('LMSTUDIO_STREAMING_ENABLED', true),
        'chunk_size' => env('LMSTUDIO_STREAMING_CHUNK_SIZE', 1024),
    ],

    /*
    |--------------------------------------------------------------------------
    | Embeddings
    |--------------------------------------------------------------------------
    |
    | Configuration for embedding generation. Make sure you have an
    | embedding model loaded in LM Studio.
    |
    */
    'embeddings' => [
        'model' => env('LMSTUDIO_EMBEDDING_MODEL', 'text-embedding-nomic-embed-text-v1.5'),
        'dimensions' => env('LMSTUDIO_EMBEDDING_DIMENSIONS', 768),
    ],

    /*
    |--------------------------------------------------------------------------
    | Health Check
    |--------------------------------------------------------------------------
    |
    | Configuration for health monitoring and status checks.
    |
    */
    'health' => [
        'enabled' => env('LMSTUDIO_HEALTH_ENABLED', true),
        'cache_ttl' => env('LMSTUDIO_HEALTH_CACHE_TTL', 60), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Configure retry behavior for failed requests.
    |
    */
    'retry' => [
        'enabled' => env('LMSTUDIO_RETRY_ENABLED', true),
        'max_attempts' => env('LMSTUDIO_RETRY_MAX_ATTEMPTS', 3),
        'delay' => env('LMSTUDIO_RETRY_DELAY', 1000), // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Enable logging of requests and responses for debugging.
    |
    */
    'logging' => [
        'enabled' => env('LMSTUDIO_LOGGING_ENABLED', false),
        'channel' => env('LMSTUDIO_LOGGING_CHANNEL', 'stack'),
        'level' => env('LMSTUDIO_LOGGING_LEVEL', 'debug'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Cache responses to improve performance and reduce redundant requests.
    |
    */
    'cache' => [
        'enabled' => env('LMSTUDIO_CACHE_ENABLED', false),
        'ttl' => env('LMSTUDIO_CACHE_TTL', 3600), // seconds
        'driver' => env('LMSTUDIO_CACHE_DRIVER', 'file'),
    ],
];
