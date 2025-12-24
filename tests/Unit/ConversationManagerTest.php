<?php

namespace MahmoudNaggar\LaravelLMStudio\Tests\Unit;

use MahmoudNaggar\LaravelLMStudio\Tests\TestCase;
use MahmoudNaggar\LaravelLMStudio\Services\ConversationManager;
use MahmoudNaggar\LaravelLMStudio\LMStudioClient;

class ConversationManagerTest extends TestCase
{
    protected ConversationManager $conversation;

    protected function setUp(): void
    {
        parent::setUp();

        $client = new LMStudioClient(config('lmstudio'));
        $this->conversation = new ConversationManager($client);
    }

    /** @test */
    public function it_can_add_messages(): void
    {
        $this->conversation->addMessage('user', 'Hello');

        $messages = $this->conversation->getMessages();

        $this->assertCount(1, $messages);
        $this->assertEquals('user', $messages[0]['role']);
        $this->assertEquals('Hello', $messages[0]['content']);
    }

    /** @test */
    public function it_can_add_user_message(): void
    {
        $this->conversation->user('Hello');

        $messages = $this->conversation->getMessages();

        $this->assertCount(1, $messages);
        $this->assertEquals('user', $messages[0]['role']);
    }

    /** @test */
    public function it_can_add_assistant_message(): void
    {
        $this->conversation->assistant('Hi there!');

        $messages = $this->conversation->getMessages();

        $this->assertCount(1, $messages);
        $this->assertEquals('assistant', $messages[0]['role']);
    }

    /** @test */
    public function it_can_add_system_message(): void
    {
        $this->conversation->system('You are helpful');

        $messages = $this->conversation->getMessages();

        $this->assertCount(1, $messages);
        $this->assertEquals('system', $messages[0]['role']);
    }

    /** @test */
    public function it_can_clear_messages(): void
    {
        $this->conversation->user('Hello');
        $this->conversation->user('World');

        $this->assertCount(2, $this->conversation->getMessages());

        $this->conversation->clear();

        $this->assertCount(0, $this->conversation->getMessages());
    }

    /** @test */
    public function it_can_get_last_message(): void
    {
        $this->conversation->user('First');
        $this->conversation->user('Last');

        $last = $this->conversation->lastMessage();

        $this->assertEquals('Last', $last['content']);
    }

    /** @test */
    public function it_can_convert_to_string(): void
    {
        $this->conversation->user('Hello');
        $this->conversation->assistant('Hi');

        $string = $this->conversation->toString();

        $this->assertStringContainsString('user: Hello', $string);
        $this->assertStringContainsString('assistant: Hi', $string);
    }

    /** @test */
    public function it_can_count_tokens(): void
    {
        $this->conversation->user('Hello world');

        $tokens = $this->conversation->countTokens();

        $this->assertIsInt($tokens);
        $this->assertGreaterThan(0, $tokens);
    }

    /** @test */
    public function it_preserves_system_message_on_clear(): void
    {
        $client = new LMStudioClient(config('lmstudio'));
        $conversation = new ConversationManager($client, [
            'system' => 'You are helpful',
        ]);

        $conversation->user('Hello');
        $conversation->clear();

        $messages = $conversation->getMessages();

        $this->assertCount(1, $messages);
        $this->assertEquals('system', $messages[0]['role']);
    }
}
