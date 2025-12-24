<?php

namespace MahmoudNaggar\LaravelLMStudio\Services;

use MahmoudNaggar\LaravelLMStudio\LMStudioClient;
use MahmoudNaggar\LaravelLMStudio\Responses\ChatResponse;

class ConversationManager
{
    protected array $messages = [];
    protected array $options;
    protected LMStudioClient $client;

    public function __construct(LMStudioClient $client, array $options = [])
    {
        $this->client = $client;
        $this->options = $options;

        // Add system message if provided
        if (isset($options['system'])) {
            $this->addMessage('system', $options['system']);
        }
    }

    /**
     * Add a message to the conversation.
     */
    public function addMessage(string $role, string $content): self
    {
        $this->messages[] = [
            'role' => $role,
            'content' => $content,
        ];

        return $this;
    }

    /**
     * Add a user message.
     */
    public function user(string $content): self
    {
        return $this->addMessage('user', $content);
    }

    /**
     * Add an assistant message.
     */
    public function assistant(string $content): self
    {
        return $this->addMessage('assistant', $content);
    }

    /**
     * Add a system message.
     */
    public function system(string $content): self
    {
        return $this->addMessage('system', $content);
    }

    /**
     * Send the conversation and get a response.
     */
    public function send(string $message = null): ChatResponse
    {
        if ($message !== null) {
            $this->addMessage('user', $message);
        }

        $options = array_merge($this->options, [
            'messages' => $this->messages,
        ]);

        $response = $this->client->chat('', $options);

        // Add assistant's response to conversation history
        $this->addMessage('assistant', $response->content());

        return $response;
    }

    /**
     * Stream the conversation.
     */
    public function sendStream(string $message, callable $callback): void
    {
        $this->addMessage('user', $message);

        $options = array_merge($this->options, [
            'messages' => $this->messages,
        ]);

        $fullResponse = '';

        $this->client->stream('', function ($chunk) use ($callback, &$fullResponse) {
            $fullResponse .= $chunk;
            $callback($chunk);
        }, $options);

        // Add assistant's response to conversation history
        $this->addMessage('assistant', $fullResponse);
    }

    /**
     * Get all messages.
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Clear the conversation.
     */
    public function clear(): self
    {
        $this->messages = [];

        // Re-add system message if it was set
        if (isset($this->options['system'])) {
            $this->addMessage('system', $this->options['system']);
        }

        return $this;
    }

    /**
     * Get the last message.
     */
    public function lastMessage(): ?array
    {
        return end($this->messages) ?: null;
    }

    /**
     * Get the conversation history as a string.
     */
    public function toString(): string
    {
        return collect($this->messages)
            ->map(fn($msg) => "{$msg['role']}: {$msg['content']}")
            ->join("\n");
    }

    /**
     * Count total tokens in conversation.
     */
    public function countTokens(): int
    {
        $total = 0;
        foreach ($this->messages as $message) {
            $total += $this->client->countTokens($message['content']);
        }
        return $total;
    }
}
