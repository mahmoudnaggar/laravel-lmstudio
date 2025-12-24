# Changelog

All notable changes to `laravel-lmstudio` will be documented in this file.

## [Unreleased]

### Added
- Initial release
- OpenAI-compatible API integration with LM Studio
- Chat completions with streaming support
- Embedding generation
- Conversation management with context
- Model listing and management
- Health monitoring and status checks
- Token counting and estimation
- Artisan commands for CLI interaction
- Comprehensive configuration options
- Caching support for responses
- Logging capabilities
- Error handling with custom exceptions
- Full test suite
- Laravel 10 and 11 support

### Features
- `chat()` - Send chat messages
- `stream()` - Stream responses in real-time
- `embedding()` - Generate vector embeddings
- `conversation()` - Multi-turn conversations
- `models()->list()` - List available models
- `health()->status()` - Check server health
- `countTokens()` - Estimate token usage

### Commands
- `lmstudio:models` - List all models
- `lmstudio:test` - Test connection
- `lmstudio:chat` - Interactive chat
- `lmstudio:load` - Load a model

## [1.0.0] - 2025-12-25

### Added
- First stable release
- Complete documentation
- Example usage code
- Contributing guidelines
- Security policy
