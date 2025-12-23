<?php

namespace App\Console\Commands\Generators;

class RabbitMQGenerator extends BaseGenerator
{
    public function generate(string $basePath, string $context, string $module): void
    {
        $replacements = ['context' => $context, 'module' => $module];
        $files = [
            "Infrastructure/Messaging/RabbitMQ/EventPublisher.php" => "Infrastructure/Messaging/publisher",
            "Infrastructure/Messaging/Listeners/{$module}CreatedListener.php" => "Infrastructure/Messaging/listener",
        ];
        $this->generateBatch($basePath, $files, $replacements);
    }
}