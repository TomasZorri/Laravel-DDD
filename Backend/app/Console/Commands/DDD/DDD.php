<?php

namespace App\Console\Commands\DDD;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Console\Commands\Generators\DomainGenerator;
use App\Console\Commands\Generators\RedisGenerator;
use App\Console\Commands\Generators\RabbitMQGenerator;

final class DDD extends Command
{
    protected $signature = 'make:ddd 
        {context : Bounded Context} 
        {module : Module Name} 
        {--r|redis : Include Redis Cache} 
        {--m|rabbitmq : Include RabbitMQ Messaging}';
    protected $description = 'Create DDD + Hexagonal structure for a module (safe & idempotent)';

    public function handle(): int
    {
        $context = Str::studly($this->argument('context'));
        $module = Str::studly($this->argument('module'));
        $basePath = base_path("src/{$context}/{$module}");

        $this->info("🚀 Iniciando generación para {$context}/{$module}...");

        try {
            // 1. Capas base
            (new DomainGenerator())->generate($basePath, $context, $module);
            $this->line("✅ Dominio, Aplicación e Infraestructura base creados.");

            // 2. Redis Opcional
            if ($this->option('redis')) {
                (new RedisGenerator())->generate($basePath, $context, $module);
                $this->line("⚡ Componentes de Redis inyectados.");
            }

            // 3. RabbitMQ Opcional
            if ($this->option('rabbitmq')) {
                (new RabbitMQGenerator())->generate($basePath, $context, $module);
                $this->line("🐇 Componentes de RabbitMQ inyectados.");
            }

            $this->info("⭐ Estructura DDD Hexagonal completada.");
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}