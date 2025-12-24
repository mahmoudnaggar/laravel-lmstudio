<?php

namespace MahmoudNaggar\LaravelLMStudio\Responses;

class ChatResponse
{
    public function __construct(
        protected string $content,
        protected array $metadata = [],
        protected ?int $tokensUsed = null,
        protected ?string $model = null,
        protected ?array $toolCalls = null
    ) {
    }

    /**
     * Get the response content.
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * Get the response as a string.
     */
    public function __toString(): string
    {
        return $this->content;
    }

    /**
     * Get metadata.
     */
    public function metadata(): array
    {
        return $this->metadata;
    }

    /**
     * Get tokens used.
     */
    public function tokensUsed(): ?int
    {
        return $this->tokensUsed;
    }

    /**
     * Get the model used.
     */
    public function model(): ?string
    {
        return $this->model;
    }

    /**
     * Get tool calls if any.
     */
    public function toolCalls(): ?array
    {
        return $this->toolCalls;
    }

    /**
     * Check if response has tool calls.
     */
    public function hasToolCalls(): bool
    {
        return !empty($this->toolCalls);
    }

    /**
     * Get finish reason.
     */
    public function finishReason(): ?string
    {
        return $this->metadata['finish_reason'] ?? null;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'metadata' => $this->metadata,
            'tokens_used' => $this->tokensUsed,
            'model' => $this->model,
            'tool_calls' => $this->toolCalls,
        ];
    }
}
