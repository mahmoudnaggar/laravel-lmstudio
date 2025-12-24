<?php

namespace MahmoudNaggar\LaravelLMStudio\Console\Commands;

use Illuminate\Console\Command;
use MahmoudNaggar\LaravelLMStudio\Facades\LMStudio;
use MahmoudNaggar\LaravelLMStudio\Exceptions\LMStudioException;

class ListModelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'lmstudio:models';

    /**
     * The console command description.
     */
    protected $description = 'List all available models in LM Studio';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->info('Fetching models from LM Studio...');

            $models = LMStudio::models()->list();

            if (empty($models)) {
                $this->warn('No models found. Make sure LM Studio is running and has models loaded.');
                return self::FAILURE;
            }

            $this->info('Available Models:');
            $this->newLine();

            $tableData = collect($models)->map(function ($model) {
                return [
                    'ID' => $model['id'],
                    'Type' => $model['object'] ?? 'model',
                    'Owner' => $model['owned_by'] ?? 'lmstudio',
                ];
            })->toArray();

            $this->table(['ID', 'Type', 'Owner'], $tableData);

            $loaded = LMStudio::models()->loaded();
            if ($loaded) {
                $this->info("Currently loaded: {$loaded}");
            }

            return self::SUCCESS;
        } catch (LMStudioException $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
