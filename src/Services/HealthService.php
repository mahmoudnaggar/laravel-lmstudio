<?php

namespace MahmoudNaggar\LaravelLMStudio\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class HealthService
{
    protected Client $client;
    protected array $config;

    public function __construct(Client $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * Check if LM Studio server is healthy.
     */
    public function isHealthy(): bool
    {
        if (!$this->config['health']['enabled']) {
            return true;
        }

        $cacheKey = 'lmstudio:health:status';
        $cacheTtl = $this->config['health']['cache_ttl'];

        return Cache::remember($cacheKey, $cacheTtl, function () {
            try {
                $response = $this->client->get('models', ['timeout' => 5]);
                return $response->getStatusCode() === 200;
            } catch (GuzzleException $e) {
                return false;
            }
        });
    }

    /**
     * Get detailed health status.
     */
    public function status(): array
    {
        $status = [
            'server' => 'unknown',
            'model' => null,
            'available_models' => 0,
            'timestamp' => now()->toIso8601String(),
        ];

        try {
            $response = $this->client->get('models', ['timeout' => 5]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                $models = $data['data'] ?? [];

                $status['server'] = 'running';
                $status['model'] = $models[0]['id'] ?? null;
                $status['available_models'] = count($models);
            }
        } catch (GuzzleException $e) {
            $status['server'] = 'offline';
            $status['error'] = $e->getMessage();
        }

        return $status;
    }

    /**
     * Ping the server.
     */
    public function ping(): bool
    {
        return $this->isHealthy();
    }

    /**
     * Get server uptime (if available).
     */
    public function uptime(): ?int
    {
        // LM Studio doesn't provide uptime information
        // This is a placeholder for future functionality
        return null;
    }

    /**
     * Clear health cache.
     */
    public function clearCache(): void
    {
        Cache::forget('lmstudio:health:status');
    }
}
