<?php

namespace MahmoudNaggar\LaravelLMStudio\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use MahmoudNaggar\LaravelLMStudio\LMStudioServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LMStudioServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'LMStudio' => \MahmoudNaggar\LaravelLMStudio\Facades\LMStudio::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup default configuration
        config()->set('lmstudio.base_url', 'http://localhost:1234/v1');
        config()->set('lmstudio.timeout', 120);
        config()->set('lmstudio.default_model', 'test-model');
    }
}
