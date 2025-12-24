<?php

namespace MahmoudNaggar\LaravelLMStudio\Exceptions;

use Exception;

class LMStudioException extends Exception
{
    /**
     * Create a new exception for connection errors.
     */
    public static function connectionFailed(string $message = ''): self
    {
        return new self(
            'Failed to connect to LM Studio server. ' .
            'Make sure LM Studio is running and the local server is enabled. ' .
            $message
        );
    }

    /**
     * Create a new exception for model errors.
     */
    public static function modelNotLoaded(): self
    {
        return new self(
            'No model is currently loaded in LM Studio. ' .
            'Please load a model through the LM Studio interface.'
        );
    }

    /**
     * Create a new exception for streaming errors.
     */
    public static function streamingNotSupported(): self
    {
        return new self(
            'Streaming is not supported or disabled. ' .
            'Check your configuration or the loaded model.'
        );
    }

    /**
     * Create a new exception for embedding errors.
     */
    public static function embeddingNotSupported(): self
    {
        return new self(
            'Embeddings are not supported by the current model. ' .
            'Please load an embedding model in LM Studio.'
        );
    }
}
