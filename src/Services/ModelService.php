<?php

namespace MahmoudNaggar\LaravelLMStudio\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use MahmoudNaggar\LaravelLMStudio\Exceptions\LMStudioException;

class ModelService
{
    protected Client $client;
    protected array $config;

    public function __construct(Client $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * List all available models.
     */
    public function list(): array
    {
        try {
            $response = $this->client->get('models');
            $data = json_decode($response->getBody()->getContents(), true);

            return collect($data['data'] ?? [])
                ->map(fn($model) => [
                    'id' => $model['id'],
                    'object' => $model['object'] ?? 'model',
                    'created' => $model['created'] ?? null,
                    'owned_by' => $model['owned_by'] ?? 'lmstudio',
                ])
                ->toArray();
        } catch (GuzzleException $e) {
            throw new LMStudioException("Failed to list models: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Get the currently loaded model.
     */
    public function loaded(): ?string
    {
        try {
            $models = $this->list();

            // LM Studio typically returns the loaded model first
            return $models[0]['id'] ?? null;
        } catch (LMStudioException $e) {
            return null;
        }
    }

    /**
     * Load a specific model.
     * Note: This requires LM Studio's API to support model loading.
     * Currently, models must be loaded manually through LM Studio UI.
     */
    public function load(string $modelId): bool
    {
        // LM Studio doesn't currently support programmatic model loading via API
        // This is a placeholder for future functionality
        throw new LMStudioException(
            'Model loading must be done through LM Studio UI. ' .
            'Please load the model manually in LM Studio.'
        );
    }

    /**
     * Unload the current model.
     * Note: This requires LM Studio's API to support model unloading.
     */
    public function unload(): bool
    {
        // LM Studio doesn't currently support programmatic model unloading via API
        // This is a placeholder for future functionality
        throw new LMStudioException(
            'Model unloading must be done through LM Studio UI. ' .
            'Please unload the model manually in LM Studio.'
        );
    }

    /**
     * Get model information.
     */
    public function info(string $modelId): ?array
    {
        $models = $this->list();

        foreach ($models as $model) {
            if ($model['id'] === $modelId) {
                return $model;
            }
        }

        return null;
    }

    /**
     * Check if a model is available.
     */
    public function isAvailable(string $modelId): bool
    {
        return $this->info($modelId) !== null;
    }

    /**
     * Get all model IDs.
     */
    public function ids(): array
    {
        return collect($this->list())
            ->pluck('id')
            ->toArray();
    }
}
