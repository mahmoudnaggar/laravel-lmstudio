<?php

namespace MahmoudNaggar\LaravelLMStudio\Tests\Unit;

use MahmoudNaggar\LaravelLMStudio\Tests\TestCase;
use MahmoudNaggar\LaravelLMStudio\Responses\ChatResponse;

class ChatResponseTest extends TestCase
{
    /** @test */
    public function it_can_get_content(): void
    {
        $response = new ChatResponse(
            content: 'Hello world',
            metadata: [],
            tokensUsed: 10,
            model: 'test-model'
        );

        $this->assertEquals('Hello world', $response->content());
    }

    /** @test */
    public function it_can_convert_to_string(): void
    {
        $response = new ChatResponse(
            content: 'Hello world',
            metadata: [],
        );

        $this->assertEquals('Hello world', (string) $response);
    }

    /** @test */
    public function it_can_get_metadata(): void
    {
        $metadata = ['id' => '123', 'created' => 1234567890];

        $response = new ChatResponse(
            content: 'Test',
            metadata: $metadata,
        );

        $this->assertEquals($metadata, $response->metadata());
    }

    /** @test */
    public function it_can_get_tokens_used(): void
    {
        $response = new ChatResponse(
            content: 'Test',
            tokensUsed: 42,
        );

        $this->assertEquals(42, $response->tokensUsed());
    }

    /** @test */
    public function it_can_get_model(): void
    {
        $response = new ChatResponse(
            content: 'Test',
            model: 'llama-3.2',
        );

        $this->assertEquals('llama-3.2', $response->model());
    }

    /** @test */
    public function it_can_check_tool_calls(): void
    {
        $response = new ChatResponse(
            content: 'Test',
            toolCalls: [['name' => 'test']],
        );

        $this->assertTrue($response->hasToolCalls());
        $this->assertIsArray($response->toolCalls());
    }

    /** @test */
    public function it_can_get_finish_reason(): void
    {
        $response = new ChatResponse(
            content: 'Test',
            metadata: ['finish_reason' => 'stop'],
        );

        $this->assertEquals('stop', $response->finishReason());
    }

    /** @test */
    public function it_can_convert_to_array(): void
    {
        $response = new ChatResponse(
            content: 'Test',
            metadata: ['id' => '123'],
            tokensUsed: 10,
            model: 'test-model',
        );

        $array = $response->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('Test', $array['content']);
        $this->assertEquals(10, $array['tokens_used']);
    }
}
