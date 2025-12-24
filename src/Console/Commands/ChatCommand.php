<?php

namespace MahmoudNaggar\LaravelLMStudio\Console\Commands;

use Illuminate\Console\Command;
use MahmoudNaggar\LaravelLMStudio\Facades\LMStudio;
use MahmoudNaggar\LaravelLMStudio\Exceptions\LMStudioException;

class ChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'lmstudio:chat 
                            {message : The message to send}
                            {--model= : The model to use}
                            {--temperature= : Temperature (0-2)}
                            {--max-tokens= : Maximum tokens to generate}
                            {--stream : Stream the response}';

    /**
     * The console command description.
     */
    protected $description = 'Send a chat message to LM Studio';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $message = $this->argument('message');

        $options = array_filter([
            'model' => $this->option('model'),
            'temperature' => $this->option('temperature') ? (float) $this->option('temperature') : null,
            'max_tokens' => $this->option('max-tokens') ? (int) $this->option('max-tokens') : null,
        ]);

        try {
            if ($this->option('stream')) {
                $this->info('Streaming response:');
                $this->newLine();

                LMStudio::stream($message, function ($chunk) {
                    $this->getOutput()->write($chunk);
                }, $options);

                $this->newLine();
            } else {
                $this->info('Sending message...');
                $this->newLine();

                $response = LMStudio::chat($message, $options);

                $this->line($response->content());
                $this->newLine();

                if ($response->tokensUsed()) {
                    $this->comment("Tokens used: {$response->tokensUsed()}");
                }

                if ($response->model()) {
                    $this->comment("Model: {$response->model()}");
                }
            }

            return self::SUCCESS;
        } catch (LMStudioException $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
