<?php

namespace MahmoudNaggar\LaravelLMStudio\Responses;

class EmbeddingResponse
{
    public function __construct(
        protected array $embedding,
        protected array $metadata = []
    ) {
    }

    /**
     * Get the embedding vector.
     */
    public function vector(): array
    {
        return $this->embedding;
    }

    /**
     * Get the embedding (alias for vector).
     */
    public function embedding(): array
    {
        return $this->embedding;
    }

    /**
     * Get the number of dimensions.
     */
    public function dimensions(): int
    {
        return count($this->embedding);
    }

    /**
     * Get metadata.
     */
    public function metadata(): array
    {
        return $this->metadata;
    }

    /**
     * Get the model used.
     */
    public function model(): ?string
    {
        return $this->metadata['model'] ?? null;
    }

    /**
     * Calculate cosine similarity with another embedding.
     */
    public function cosineSimilarity(array $otherEmbedding): float
    {
        if (count($this->embedding) !== count($otherEmbedding)) {
            throw new \InvalidArgumentException('Embeddings must have the same dimensions');
        }

        $dotProduct = 0;
        $magnitudeA = 0;
        $magnitudeB = 0;

        for ($i = 0; $i < count($this->embedding); $i++) {
            $dotProduct += $this->embedding[$i] * $otherEmbedding[$i];
            $magnitudeA += $this->embedding[$i] ** 2;
            $magnitudeB += $otherEmbedding[$i] ** 2;
        }

        $magnitudeA = sqrt($magnitudeA);
        $magnitudeB = sqrt($magnitudeB);

        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0;
        }

        return $dotProduct / ($magnitudeA * $magnitudeB);
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'embedding' => $this->embedding,
            'dimensions' => $this->dimensions(),
            'metadata' => $this->metadata,
        ];
    }
}
