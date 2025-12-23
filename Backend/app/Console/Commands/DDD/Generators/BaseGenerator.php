<?php

namespace App\Console\Commands\Generators;

use Illuminate\Support\Facades\File;

abstract class BaseGenerator
{
    protected function getStub(string $name): string
    {
        $path = base_path("app/Console/Commands/DDD/stubs/{$name}.stub");
        if (!File::exists($path)) {
            throw new \Exception("Stub no encontrado: {$path}");
        }
        return File::get($path);
    }

    protected function populateStub(string $stub, array $replacements): string
    {
        foreach ($replacements as $key => $value) {
            $stub = str_replace("{{{$key}}}", $value, $stub);
        }
        return $stub;
    }

    protected function createFile(string $path, string $content): void
    {
        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }
        if (!File::exists($path)) {
            File::put($path, $content);
        }
    }

    protected function generateBatch(string $basePath, array $files, array $replacements): void
    {
        foreach ($files as $destination => $stubName) {
            $content = $this->populateStub($this->getStub($stubName), $replacements);
            $this->createFile("{$basePath}/{$destination}", $content);
        }
    }
}