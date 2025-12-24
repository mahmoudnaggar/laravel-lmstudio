<?php

namespace MahmoudNaggar\LaravelLMStudio\Console\Commands;

use Illuminate\Console\Command;
use MahmoudNaggar\LaravelLMStudio\Facades\LMStudio;
use MahmoudNaggar\LaravelLMStudio\Exceptions\LMStudioException;

class LoadModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'lmstudio:load {model : The model ID to load}';

    /**
     * The console command description.
     */
    protected $description = 'Load a specific model in LM Studio';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $modelId = $this->argument('model');

        $this->info("Attempting to load model: {$modelId}");
        $this->newLine();

        try {
            // Check if model is available
            if (!LMStudio::models()->isAvailable($modelId)) {
                $this->error("Model '{$modelId}' is not available.");
                $this->newLine();
                $this->info('Available models:');

                $models = LMStudio::models()->ids();
                foreach ($models as $model) {
                    $this->line("  - {$model}");
                }

                return self::FAILURE;
            }

            // Attempt to load (note: this may not be supported by LM Studio API)
            LMStudio::models()->load($modelId);

            $this->info("Model '{$modelId}' loaded successfully!");

            return self::SUCCESS;
        } catch (LMStudioException $e) {
            $this->warn($e->getMessage());
            $this->newLine();
            $this->info('Note: Model loading must currently be done through the LM Studio UI.');
            $this->info('Please load the model manually in LM Studio.');

            return self::FAILURE;
        }
    }
}
