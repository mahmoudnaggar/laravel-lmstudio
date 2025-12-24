# Laravel LM Studio

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mahmoudnaggar/laravel-lmstudio.svg?style=flat-square)](https://packagist.org/packages/mahmoudnaggar/laravel-lmstudio)
[![Total Downloads](https://img.shields.io/packagist/dt/mahmoudnaggar/laravel-lmstudio.svg?style=flat-square)](https://packagist.org/packages/mahmoudnaggar/laravel-lmstudio)
[![License](https://img.shields.io/packagist/l/mahmoudnaggar/laravel-lmstudio.svg?style=flat-square)](https://packagist.org/packages/mahmoudnaggar/laravel-lmstudio)

**Advanced LM Studio integration for Laravel** - Run powerful local LLMs with a clean, Laravel-friendly API. Perfect for privacy-focused AI applications, offline development, and cost-effective AI solutions.

## 🚀 Features

- ✅ **OpenAI-Compatible API** - Drop-in replacement for OpenAI with local models
- ✅ **Model Management** - List, load, and switch between models programmatically
- ✅ **Streaming Support** - Real-time token streaming for chat responses
- ✅ **Embeddings** - Generate vector embeddings for semantic search
- ✅ **Conversation Management** - Maintain context across multiple messages
- ✅ **Health Monitoring** - Check LM Studio server status and loaded models
- ✅ **Token Counting** - Estimate and track token usage
- ✅ **Artisan Commands** - CLI tools for model management and testing
- ✅ **Comprehensive Testing** - Full test suite included
- ✅ **Laravel 10 & 11** - Full support for latest Laravel versions

## 📋 Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x
- [LM Studio](https://lmstudio.ai/) installed and running
- LM Studio local server enabled (default: `http://localhost:1234`)

## 📦 Installation

Install the package via Composer:

```bash
composer require mahmoudnaggar/laravel-lmstudio
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=lmstudio-config
```

## ⚙️ Configuration

Update your `.env` file:

```env
LMSTUDIO_BASE_URL=http://localhost:1234/v1
LMSTUDIO_TIMEOUT=120
LMSTUDIO_DEFAULT_MODEL=llama-3.2-3b-instruct
LMSTUDIO_MAX_TOKENS=2048
LMSTUDIO_TEMPERATURE=0.7
```

The configuration file (`config/lmstudio.php`) provides extensive customization options.

## 🎯 Quick Start

### Basic Chat

```php
use MahmoudNaggar\LaravelLMStudio\Facades\LMStudio;

// Simple chat
$response = LMStudio::chat('What is Laravel?');
echo $response->content();

// With options
$response = LMStudio::chat('Explain quantum computing', [
    'model' => 'llama-3.2-3b-instruct',
    'temperature' => 0.8,
    'max_tokens' => 500,
]);
```

### Streaming Responses

```php
LMStudio::stream('Write a story about AI', function ($chunk) {
    echo $chunk; // Output each token as it arrives
});
```

### Conversations

```php
$conversation = LMStudio::conversation();

$conversation->addMessage('user', 'Hello! My name is John.');
$response1 = $conversation->send();

$conversation->addMessage('user', 'What is my name?');
$response2 = $conversation->send(); // Will remember "John"
```

### Embeddings

```php
$embedding = LMStudio::embedding('Laravel is a PHP framework');
$vector = $embedding->vector(); // Array of floats
$dimensions = $embedding->dimensions(); // 384, 768, etc.
```

### Model Management

```php
// List available models
$models = LMStudio::models()->list();

// Get loaded model
$currentModel = LMStudio::models()->loaded();

// Load a specific model
LMStudio::models()->load('mistral-7b-instruct');

// Unload current model
LMStudio::models()->unload();
```

### Health Checks

```php
// Check if LM Studio is running
if (LMStudio::health()->isHealthy()) {
    echo "LM Studio is running!";
}

// Get detailed status
$status = LMStudio::health()->status();
echo "Server: " . $status['server'];
echo "Model: " . $status['model'];
```

## 🛠️ Artisan Commands

### List Models

```bash
php artisan lmstudio:models
```

### Test Connection

```bash
php artisan lmstudio:test
```

### Chat from CLI

```bash
php artisan lmstudio:chat "What is the meaning of life?"
```

### Load Model

```bash
php artisan lmstudio:load mistral-7b-instruct
```

## 📚 Advanced Usage

### Custom System Prompts

```php
$response = LMStudio::chat('Hello', [
    'system' => 'You are a helpful coding assistant specializing in Laravel.',
]);
```

### Function Calling (Tool Use)

```php
$response = LMStudio::chat('What is the weather in Paris?', [
    'tools' => [
        [
            'type' => 'function',
            'function' => [
                'name' => 'get_weather',
                'description' => 'Get current weather',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'location' => ['type' => 'string'],
                    ],
                ],
            ],
        ],
    ],
]);

if ($response->hasToolCalls()) {
    $toolCalls = $response->toolCalls();
    // Process tool calls...
}
```

### Token Counting

```php
$text = "This is a sample text";
$tokens = LMStudio::countTokens($text);

if (LMStudio::withinTokenLimit($text, 1000)) {
    // Process the text
}
```

### Batch Processing

```php
$prompts = ['Question 1', 'Question 2', 'Question 3'];

$responses = collect($prompts)->map(function ($prompt) {
    return LMStudio::chat($prompt);
});
```

### Error Handling

```php
use MahmoudNaggar\LaravelLMStudio\Exceptions\LMStudioException;

try {
    $response = LMStudio::chat('Hello');
} catch (LMStudioException $e) {
    Log::error('LM Studio error: ' . $e->getMessage());
    // Fallback logic
}
```

## 🧪 Testing

Run the test suite:

```bash
composer test
```

Run code formatting:

```bash
composer format
```

## 📖 Documentation

For detailed documentation, visit the [Wiki](https://github.com/mahmoudnaggar/laravel-lmstudio/wiki).

## 🤝 Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## 🔒 Security

If you discover any security-related issues, please email mahmoud@example.com instead of using the issue tracker.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🙏 Credits

- [Mahmoud Naggar](https://github.com/mahmoudnaggar)
- [All Contributors](../../contributors)

## 🌟 Show Your Support

If this package helps you, please consider giving it a ⭐️ on GitHub!

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/mahmoudnaggar/laravel-lmstudio/issues)
- **Discussions**: [GitHub Discussions](https://github.com/mahmoudnaggar/laravel-lmstudio/discussions)

---

Made with ❤️ for the Laravel community
