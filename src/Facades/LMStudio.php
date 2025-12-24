<?php

namespace MahmoudNaggar\LaravelLMStudio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \MahmoudNaggar\LaravelLMStudio\Responses\ChatResponse chat(string $message, array $options = [])
 * @method static void stream(string $message, callable $callback, array $options = [])
 * @method static \MahmoudNaggar\LaravelLMStudio\Responses\EmbeddingResponse embedding(string $text, array $options = [])
 * @method static \MahmoudNaggar\LaravelLMStudio\Services\ConversationManager conversation(array $options = [])
 * @method static \MahmoudNaggar\LaravelLMStudio\Services\ModelService models()
 * @method static \MahmoudNaggar\LaravelLMStudio\Services\HealthService health()
 * @method static int countTokens(string $text)
 * @method static bool withinTokenLimit(string $text, int $limit)
 *
 * @see \MahmoudNaggar\LaravelLMStudio\LMStudioClient
 */
class LMStudio extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'lmstudio';
    }
}
