<?php

namespace MahmoudNaggar\LaravelLMStudio\Tests\Unit;

use MahmoudNaggar\LaravelLMStudio\Tests\TestCase;
use MahmoudNaggar\LaravelLMStudio\Responses\EmbeddingResponse;

class EmbeddingResponseTest extends TestCase
{
    /** @test */
    public function it_can_get_vector(): void
    {
        $vector = [0.1, 0.2, 0.3];
        $response = new EmbeddingResponse($vector);

        $this->assertEquals($vector, $response->vector());
        $this->assertEquals($vector, $response->embedding());
    }

    /** @test */
    public function it_can_get_dimensions(): void
    {
        $vector = array_fill(0, 768, 0.1);
        $response = new EmbeddingResponse($vector);

        $this->assertEquals(768, $response->dimensions());
    }

    /** @test */
    public function it_can_calculate_cosine_similarity(): void
    {
        $vector1 = [1.0, 0.0, 0.0];
        $vector2 = [1.0, 0.0, 0.0];

        $response = new EmbeddingResponse($vector1);
        $similarity = $response->cosineSimilarity($vector2);

        $this->assertEquals(1.0, $similarity);
    }

    /** @test */
    public function it_calculates_zero_similarity_for_orthogonal_vectors(): void
    {
        $vector1 = [1.0, 0.0];
        $vector2 = [0.0, 1.0];

        $response = new EmbeddingResponse($vector1);
        $similarity = $response->cosineSimilarity($vector2);

        $this->assertEquals(0.0, $similarity);
    }

    /** @test */
    public function it_throws_exception_for_different_dimensions(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $vector1 = [1.0, 0.0];
        $vector2 = [1.0, 0.0, 0.0];

        $response = new EmbeddingResponse($vector1);
        $response->cosineSimilarity($vector2);
    }

    /** @test */
    public function it_can_get_metadata(): void
    {
        $metadata = ['model' => 'test-embedding'];
        $response = new EmbeddingResponse([0.1, 0.2], $metadata);

        $this->assertEquals($metadata, $response->metadata());
        $this->assertEquals('test-embedding', $response->model());
    }

    /** @test */
    public function it_can_convert_to_array(): void
    {
        $vector = [0.1, 0.2, 0.3];
        $response = new EmbeddingResponse($vector);

        $array = $response->toArray();

        $this->assertIsArray($array);
        $this->assertEquals($vector, $array['embedding']);
        $this->assertEquals(3, $array['dimensions']);
    }
}
