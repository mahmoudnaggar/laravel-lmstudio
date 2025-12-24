<?php

namespace MahmoudNaggar\LaravelLMStudio;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use MahmoudNaggar\LaravelLMStudio\Exceptions\LMStudioException;
use MahmoudNaggar\LaravelLMStudio\Responses\ChatResponse;
use MahmoudNaggar\LaravelLMStudio\Responses\EmbeddingResponse;
use MahmoudNaggar\LaravelLMStudio\Services\ConversationManager;
use MahmoudNaggar\LaravelLMStudio\Services\HealthService;
use MahmoudNaggar\LaravelLMStudio\Services\ModelService;

class LMStudioClient
{
    protected Client $client;
    protected array $config;
    protected ?ModelService $modelService = null;
    protected ?HealthService $healthService = null;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $config['base_url'],
            'timeout' => $config['timeout'],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Send a chat message and get a response.
     */
    public function chat(string $message, array $options = []): ChatResponse
    {
        $cacheKey = $this->getCacheKey('chat', $message, $options);

        if ($this->isCacheEnabled() && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $payload = $this->buildChatPayload($message, $options);

            $this->log('info', 'Sending chat request', ['payload' => $payload]);

            $response = $this->client->post('chat/completions', [
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $this->log('info', 'Received chat response', ['data' => $data]);

            $chatResponse = new ChatResponse(
                content: $data['choices'][0]['message']['content'] ?? '',
                metadata: [
                    'id' => $data['id'] ?? null,
                    'created' => $data['created'] ?? null,
                    'finish_reason' => $data['choices'][0]['finish_reason'] ?? null,
                ],
                tokensUsed: $data['usage']['total_tokens'] ?? null,
                model: $data['model'] ?? null,
                toolCalls: $data['choices'][0]['message']['tool_calls'] ?? null
            );

            if ($this->isCacheEnabled()) {
                Cache::put($cacheKey, $chatResponse, $this->config['cache']['ttl']);
            }

            return $chatResponse;
        } catch (GuzzleException $e) {
            $this->log('error', 'Chat request failed', ['error' => $e->getMessage()]);
            throw new LMStudioException("LM Studio API Error: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Stream a chat message with real-time token generation.
     */
    public function stream(string $message, callable $callback, array $options = []): void
    {
        if (!$this->config['streaming']['enabled']) {
            throw new LMStudioException('Streaming is disabled in configuration');
        }

        try {
            $payload = $this->buildChatPayload($message, $options);
            $payload['stream'] = true;

            $this->log('info', 'Starting stream request', ['payload' => $payload]);

            $response = $this->client->post('chat/completions', [
                'json' => $payload,
                'stream' => true,
            ]);

            $body = $response->getBody();

            while (!$body->eof()) {
                $line = $this->readLine($body);

                if (empty($line) || !str_starts_with($line, 'data: ')) {
                    continue;
                }

                $data = substr($line, 6);

                if ($data === '[DONE]') {
                    break;
                }

                $json = json_decode($data, true);

                if (isset($json['choices'][0]['delta']['content'])) {
                    $callback($json['choices'][0]['delta']['content']);
                }
            }

            $this->log('info', 'Stream completed');
        } catch (GuzzleException $e) {
            $this->log('error', 'Stream request failed', ['error' => $e->getMessage()]);
            throw new LMStudioException("LM Studio Streaming Error: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Generate embeddings for text.
     */
    public function embedding(string $text, array $options = []): EmbeddingResponse
    {
        $cacheKey = $this->getCacheKey('embedding', $text, $options);

        if ($this->isCacheEnabled() && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $payload = [
                'input' => $text,
                'model' => $options['model'] ?? $this->config['embeddings']['model'],
            ];

            $this->log('info', 'Sending embedding request', ['payload' => $payload]);

            $response = $this->client->post('embeddings', [
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $this->log('info', 'Received embedding response');

            $embeddingResponse = new EmbeddingResponse(
                embedding: $data['data'][0]['embedding'],
                metadata: [
                    'model' => $data['model'],
                    'usage' => $data['usage'] ?? null,
                ]
            );

            if ($this->isCacheEnabled()) {
                Cache::put($cacheKey, $embeddingResponse, $this->config['cache']['ttl']);
            }

            return $embeddingResponse;
        } catch (GuzzleException $e) {
            $this->log('error', 'Embedding request failed', ['error' => $e->getMessage()]);
            throw new LMStudioException("LM Studio Embedding Error: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Create a new conversation manager.
     */
    public function conversation(array $options = []): ConversationManager
    {
        return new ConversationManager($this, $options);
    }

    /**
     * Get the model service.
     */
    public function models(): ModelService
    {
        if (!$this->modelService) {
            $this->modelService = new ModelService($this->client, $this->config);
        }

        return $this->modelService;
    }

    /**
     * Get the health service.
     */
    public function health(): HealthService
    {
        if (!$this->healthService) {
            $this->healthService = new HealthService($this->client, $this->config);
        }

        return $this->healthService;
    }

    /**
     * Count tokens in a message (estimation).
     */
    public function countTokens(string $text): int
    {
        // Rough estimation: ~4 characters per token for English text
        return (int) ceil(strlen($text) / 4);
    }

    /**
     * Check if text is within token limit.
     */
    public function withinTokenLimit(string $text, int $limit): bool
    {
        return $this->countTokens($text) <= $limit;
    }

    /**
     * Build the chat payload.
     */
    protected function buildChatPayload(string $message, array $options): array
    {
        $payload = [
            'model' => $options['model'] ?? $this->config['default_model'],
            'messages' => $options['messages'] ?? [
                ['role' => 'user', 'content' => $message]
            ],
        ];

        // Add system message if provided
        if (isset($options['system'])) {
            array_unshift($payload['messages'], [
                'role' => 'system',
                'content' => $options['system']
            ]);
        }

        // Add optional parameters with defaults from config
        $payload['max_tokens'] = $options['max_tokens'] ?? $this->config['defaults']['max_tokens'];
        $payload['temperature'] = $options['temperature'] ?? $this->config['defaults']['temperature'];
        $payload['top_p'] = $options['top_p'] ?? $this->config['defaults']['top_p'];
        $payload['frequency_penalty'] = $options['frequency_penalty'] ?? $this->config['defaults']['frequency_penalty'];
        $payload['presence_penalty'] = $options['presence_penalty'] ?? $this->config['defaults']['presence_penalty'];

        // Add tools/functions if provided
        if (isset($options['tools'])) {
            $payload['tools'] = $options['tools'];
        }

        if (isset($options['tool_choice'])) {
            $payload['tool_choice'] = $options['tool_choice'];
        }

        return $payload;
    }

    /**
     * Read a line from a stream.
     */
    protected function readLine($stream): string
    {
        $buffer = '';

        while (!$stream->eof()) {
            $byte = $stream->read(1);

            if ($byte === "\n") {
                break;
            }

            $buffer .= $byte;
        }

        return trim($buffer);
    }

    /**
     * Generate cache key.
     */
    protected function getCacheKey(string $type, string $content, array $options): string
    {
        return 'lmstudio:' . $type . ':' . md5($content . json_encode($options));
    }

    /**
     * Check if caching is enabled.
     */
    protected function isCacheEnabled(): bool
    {
        return $this->config['cache']['enabled'] ?? false;
    }

    /**
     * Log a message if logging is enabled.
     */
    protected function log(string $level, string $message, array $context = []): void
    {
        if ($this->config['logging']['enabled'] ?? false) {
            Log::channel($this->config['logging']['channel'])
                ->log($level, '[LM Studio] ' . $message, $context);
        }
    }

    /**
     * Get the HTTP client.
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Get the configuration.
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
