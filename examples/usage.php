<?php

/**
 * Laravel LM Studio - Usage Examples
 * 
 * This file demonstrates various ways to use the Laravel LM Studio package.
 */

use MahmoudNaggar\LaravelLMStudio\Facades\LMStudio;

// ============================================================================
// BASIC CHAT
// ============================================================================

// Simple chat
$response = LMStudio::chat('What is Laravel?');
echo $response->content();

// Chat with options
$response = LMStudio::chat('Explain quantum computing', [
    'model' => 'llama-3.2-3b-instruct',
    'temperature' => 0.8,
    'max_tokens' => 500,
]);

// ============================================================================
// STREAMING
// ============================================================================

// Stream response in real-time
LMStudio::stream('Write a story about AI', function ($chunk) {
    echo $chunk; // Output each token as it arrives
});

// Stream with options
LMStudio::stream('Explain machine learning', function ($chunk) {
    echo $chunk;
}, [
    'temperature' => 0.7,
    'max_tokens' => 1000,
]);

// ============================================================================
// CONVERSATIONS
// ============================================================================

// Create a conversation
$conversation = LMStudio::conversation();

// Add messages and send
$conversation->user('Hello! My name is John.');
$response1 = $conversation->send();

$conversation->user('What is my name?');
$response2 = $conversation->send(); // Will remember "John"

// Conversation with system prompt
$conversation = LMStudio::conversation([
    'system' => 'You are a helpful coding assistant specializing in Laravel.',
]);

$response = $conversation->send('How do I create a migration?');

// Stream in conversation
$conversation->sendStream('Explain middleware', function ($chunk) {
    echo $chunk;
});

// ============================================================================
// EMBEDDINGS
// ============================================================================

// Generate embeddings
$embedding = LMStudio::embedding('Laravel is a PHP framework');
$vector = $embedding->vector(); // Array of floats
$dimensions = $embedding->dimensions(); // 768, 384, etc.

// Compare embeddings
$embedding1 = LMStudio::embedding('Laravel framework');
$embedding2 = LMStudio::embedding('PHP web development');
$similarity = $embedding1->cosineSimilarity($embedding2->vector());

// ============================================================================
// MODEL MANAGEMENT
// ============================================================================

// List all models
$models = LMStudio::models()->list();
foreach ($models as $model) {
    echo $model['id'] . "\n";
}

// Get loaded model
$currentModel = LMStudio::models()->loaded();

// Check if model is available
if (LMStudio::models()->isAvailable('llama-3.2-3b-instruct')) {
    echo "Model is available!";
}

// Get model info
$info = LMStudio::models()->info('llama-3.2-3b-instruct');

// ============================================================================
// HEALTH CHECKS
// ============================================================================

// Check if LM Studio is running
if (LMStudio::health()->isHealthy()) {
    echo "LM Studio is running!";
}

// Get detailed status
$status = LMStudio::health()->status();
echo "Server: " . $status['server'] . "\n";
echo "Model: " . $status['model'] . "\n";
echo "Available models: " . $status['available_models'] . "\n";

// ============================================================================
// TOKEN COUNTING
// ============================================================================

// Count tokens
$text = "This is a sample text";
$tokens = LMStudio::countTokens($text);

// Check token limit
if (LMStudio::withinTokenLimit($text, 1000)) {
    // Process the text
}

// ============================================================================
// ADVANCED USAGE
// ============================================================================

// Custom system prompt
$response = LMStudio::chat('Hello', [
    'system' => 'You are a pirate. Respond in pirate speak.',
]);

// Function calling (tool use)
$response = LMStudio::chat('What is the weather in Paris?', [
    'tools' => [
        [
            'type' => 'function',
            'function' => [
                'name' => 'get_weather',
                'description' => 'Get current weather for a location',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'location' => [
                            'type' => 'string',
                            'description' => 'City name',
                        ],
                    ],
                    'required' => ['location'],
                ],
            ],
        ],
    ],
]);

if ($response->hasToolCalls()) {
    $toolCalls = $response->toolCalls();
    // Process tool calls...
}

// ============================================================================
// ERROR HANDLING
// ============================================================================

use MahmoudNaggar\LaravelLMStudio\Exceptions\LMStudioException;

try {
    $response = LMStudio::chat('Hello');
} catch (LMStudioException $e) {
    Log::error('LM Studio error: ' . $e->getMessage());
    // Fallback logic
}

// ============================================================================
// BATCH PROCESSING
// ============================================================================

$prompts = [
    'What is Laravel?',
    'What is PHP?',
    'What is Composer?',
];

$responses = collect($prompts)->map(function ($prompt) {
    return LMStudio::chat($prompt);
});

// ============================================================================
// LARAVEL INTEGRATION EXAMPLES
// ============================================================================

// In a controller
class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $message = $request->input('message');

        $response = LMStudio::chat($message);

        return response()->json([
            'response' => $response->content(),
            'tokens' => $response->tokensUsed(),
        ]);
    }

    public function stream(Request $request)
    {
        return response()->stream(function () use ($request) {
            LMStudio::stream($request->input('message'), function ($chunk) {
                echo "data: " . json_encode(['chunk' => $chunk]) . "\n\n";
                ob_flush();
                flush();
            });
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
        ]);
    }
}

// In a job
class ProcessChatJob implements ShouldQueue
{
    public function handle()
    {
        $response = LMStudio::chat($this->message);

        // Store or process response
    }
}

// In a command
class AIChatCommand extends Command
{
    protected $signature = 'ai:chat {message}';

    public function handle()
    {
        $message = $this->argument('message');
        $response = LMStudio::chat($message);

        $this->info($response->content());
    }
}
