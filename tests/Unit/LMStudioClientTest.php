<?php

namespace MahmoudNaggar\LaravelLMStudio\Tests\Unit;

use MahmoudNaggar\LaravelLMStudio\Tests\TestCase;
use MahmoudNaggar\LaravelLMStudio\LMStudioClient;
use MahmoudNaggar\LaravelLMStudio\Facades\LMStudio;

class LMStudioClientTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated(): void
    {
        $client = new LMStudioClient(config('lmstudio'));

        $this->assertInstanceOf(LMStudioClient::class, $client);
    }

    /** @test */
    public function it_can_count_tokens(): void
    {
        $client = new LMStudioClient(config('lmstudio'));

        $tokens = $client->countTokens('Hello world');

        $this->assertIsInt($tokens);
        $this->assertGreaterThan(0, $tokens);
    }

    /** @test */
    public function it_can_check_token_limits(): void
    {
        $client = new LMStudioClient(config('lmstudio'));

        $this->assertTrue($client->withinTokenLimit('Short text', 1000));
        $this->assertFalse($client->withinTokenLimit(str_repeat('word ', 1000), 10));
    }

    /** @test */
    public function it_can_create_conversation(): void
    {
        $client = new LMStudioClient(config('lmstudio'));

        $conversation = $client->conversation();

        $this->assertInstanceOf(\MahmoudNaggar\LaravelLMStudio\Services\ConversationManager::class, $conversation);
    }

    /** @test */
    public function it_can_access_model_service(): void
    {
        $client = new LMStudioClient(config('lmstudio'));

        $models = $client->models();

        $this->assertInstanceOf(\MahmoudNaggar\LaravelLMStudio\Services\ModelService::class, $models);
    }

    /** @test */
    public function it_can_access_health_service(): void
    {
        $client = new LMStudioClient(config('lmstudio'));

        $health = $client->health();

        $this->assertInstanceOf(\MahmoudNaggar\LaravelLMStudio\Services\HealthService::class, $health);
    }

    /** @test */
    public function facade_works(): void
    {
        $this->assertInstanceOf(LMStudioClient::class, LMStudio::getFacadeRoot());
    }
}
