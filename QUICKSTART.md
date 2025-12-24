# Quick Start Guide

## Installation

1. **Install the package:**
   ```bash
   composer require mahmoudnaggar/laravel-lmstudio
   ```

2. **Publish configuration:**
   ```bash
   php artisan vendor:publish --tag=lmstudio-config
   ```

3. **Configure environment:**
   ```env
   LMSTUDIO_BASE_URL=http://localhost:1234/v1/
   LMSTUDIO_DEFAULT_MODEL=llama-3.2-3b-instruct
   ```

## Prerequisites

1. **Download and install LM Studio** from [lmstudio.ai](https://lmstudio.ai/)
2. **Download a model** (e.g., Llama 3.2, Mistral, Phi)
3. **Start the local server** in LM Studio (Server tab → Start Server)

## Basic Usage

### Simple Chat

```php
use MahmoudNaggar\LaravelLMStudio\Facades\LMStudio;

$response = LMStudio::chat('What is Laravel?');
echo $response->content();
```

### Streaming Response

```php
LMStudio::stream('Tell me a story', function ($chunk) {
    echo $chunk;
});
```

### Conversation

```php
$conversation = LMStudio::conversation();

$conversation->user('My name is John');
$response1 = $conversation->send();

$conversation->user('What is my name?');
$response2 = $conversation->send(); // Remembers "John"
```

### Embeddings

```php
$embedding = LMStudio::embedding('Laravel is awesome');
$vector = $embedding->vector(); // Array of floats
```

## Testing Connection

```bash
php artisan lmstudio:test
```

## Common Issues

### "Connection refused"
- Make sure LM Studio is running
- Check that the local server is started in LM Studio
- Verify the base URL in your `.env` file

### "No model loaded"
- Load a model in LM Studio UI
- Check available models: `php artisan lmstudio:models`

### Slow responses
- Local models can be slower than cloud APIs
- Increase timeout in config: `LMSTUDIO_TIMEOUT=300`
- Consider using a smaller model

## Next Steps

- Read the full [README.md](README.md)
- Check [examples/usage.php](examples/usage.php) for more examples
- Explore available [Artisan commands](#artisan-commands)

## Artisan Commands

```bash
# List available models
php artisan lmstudio:models

# Test connection
php artisan lmstudio:test

# Send a chat message
php artisan lmstudio:chat "Hello, AI!"

# Stream a response
php artisan lmstudio:chat "Tell me a story" --stream
```

## Support

- **Issues**: [GitHub Issues](https://github.com/mahmoudnaggar/laravel-lmstudio/issues)
- **Discussions**: [GitHub Discussions](https://github.com/mahmoudnaggar/laravel-lmstudio/discussions)
