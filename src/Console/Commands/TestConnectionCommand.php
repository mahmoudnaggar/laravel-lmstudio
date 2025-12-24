<?php

namespace MahmoudNaggar\LaravelLMStudio\Console\Commands;

use Illuminate\Console\Command;
use MahmoudNaggar\LaravelLMStudio\Facades\LMStudio;
use MahmoudNaggar\LaravelLMStudio\Exceptions\LMStudioException;

class TestConnectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'lmstudio:test';

    /**
     * The console command description.
     */
    protected $description = 'Test connection to LM Studio server';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Testing connection to LM Studio...');
        $this->newLine();

        // Test server health
        $this->line('Checking server status...');
        $status = LMStudio::health()->status();

        if ($status['server'] === 'running') {
            $this->info('✓ Server is running');
        } else {
            $this->error('✗ Server is offline');
            if (isset($status['error'])) {
                $this->error('  Error: ' . $status['error']);
            }
            return self::FAILURE;
        }

        // Check loaded model
        if ($status['model']) {
            $this->info("✓ Model loaded: {$status['model']}");
        } else {
            $this->warn('⚠ No model currently loaded');
        }

        $this->info("✓ Available models: {$status['available_models']}");

        // Test chat
        $this->newLine();
        $this->line('Testing chat completion...');

        try {
            $response = LMStudio::chat('Say "Hello World" and nothing else.');
            $this->info('✓ Chat test successful');
            $this->line('  Response: ' . substr($response->content(), 0, 100));

            if ($response->tokensUsed()) {
                $this->line("  Tokens used: {$response->tokensUsed()}");
            }
        } catch (LMStudioException $e) {
            $this->error('✗ Chat test failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->newLine();
        $this->info('All tests passed! LM Studio is working correctly.');

        return self::SUCCESS;
    }
}
