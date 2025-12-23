<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class MakeDDD extends Command
{
    protected $isRedis = false;
    protected $isRabbitMQ = false;
    protected $signature = 'make:ddds
        {context : Bounded Context (e.g. lms)}
        {module : Module (e.g. course)}
        {--s|skip-files : Only create folders}
        {--r|redis : Only create folders & redis files}
        {--m|rabbitmq : Only create folders & rabbitmq files}
    ';

    protected $description = 'Create DDD + Hexagonal structure for a module (safe & idempotent)';

    public function handle(): int
    {
        $context = Str::studly($this->argument('context'));
        $module = Str::studly($this->argument('module'));
        $skip = $this->option('skip-files');
        $this->isRedis = $this->option('redis') ? true : false;
        $this->isRabbitMQ = $this->option('rabbitmq') ? true : false;

        $base = base_path("src/{$context}/{$module}");

        $this->info("📦 Creating {$context}/{$module}");

        $this->createRoutes($context, $module);
        $this->createProviders($context, $module);
        $this->createBaseFolders($base);

        if (!$skip) {
            $this->createBaseFiles($base, $context, $module);
        } else {
            $this->warn('↪ Skipping files (--skip-files)');
        }

        if ($this->isRedis) {
            $this->createRedis($base, $context, $module);
        } else {
            $this->warn('↪ Skipping redis files (--redis)');
        }

        if ($this->isRabbitMQ) {
            $this->createRabbitMQ($base, $context, $module);
        } else {
            $this->warn('↪ Skipping rabbitmq files (--rabbitmq)');
        }

        $this->info('✅ Done');

        return Command::SUCCESS;
    }


    /* -------------------------------- Utils -------------------------------- */
    private function createFolders(array $dirs, string $base): void
    {
        foreach ($dirs as $dir) {
            $path = "{$base}/{$dir}";
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                $this->line("📁 {$dir}");
            }
        }
    }

    private function createFiles(array $files, string $base): void
    {
        foreach ($files as $path => $content) {
            $full = "{$base}/{$path}";
            if (!File::exists($full)) {
                File::put($full, $content);
                $this->line("📄 {$path}");
            }
        }
    }


    /* -------------------------------- Base Directories -------------------------------- */
    private function createProviders(string $c, string $m): void
    {
        $path = base_path('bootstrap/providers.php');

        if (!File::exists($path)) {
            $this->error('❌ bootstrap/providers.php not found');
            return;
        }

        $content = File::get($path);

        // Armar Providers
        $providers = ["Src\\{$c}\\{$m}\\Infrastructure\\Providers\\PersistenceServiceProvider::class,"];
        if ($this->isRedis || $this->isRabbitMQ) {
            $providers[] = "Src\\{$c}\\{$m}\\Infrastructure\\Providers\\EventServiceProvider::class,";
        }
        if ($this->isRedis) {
            $providers[] = "Src\\{$c}\\{$m}\\Infrastructure\\Providers\\CacheServiceProvider::class,";
            $providers[] = "Src\\{$c}\\{$m}\\Infrastructure\\Providers\\CacheDecoratorProvider::class,";
        }
        if ($this->isRabbitMQ) {
            $providers[] = "Src\\{$c}\\{$m}\\Infrastructure\\Providers\\MessagingServiceProvider::class,";
        }


        foreach ($providers as $provider) {
            // Evitar duplicados
            if (str_contains($content, $provider)) {
                continue;
            }

            // Insertar ANTES del cierre ];
            $content = preg_replace(
                '/\];\s*$/',
                "    {$provider}\n];",
                $content
            );
        }

        File::put($path, $content);

        $this->info('🧩 Providers registered');
    }

    private function createRoutes(string $c, string $m): void
    {
        $mainRoutesPath = base_path('routes/api.php');

        # Si se especifica versionado en atributo colocar dentro de un group 
        # "Route::prefix('v1')->group(function () {});", sino colocarlo fuera

        $prefix = "{$c}/{$m}";
        $routeLine = "Route::prefix('{$prefix}')->group(base_path('src/{$prefix}/Infrastructure/Http/Routes/api.php'));";

        // Leer contenido actual
        $currentContent = File::exists($mainRoutesPath) ? File::get($mainRoutesPath) : '';

        // Verificar si ya existe
        if (str_contains($currentContent, $routeLine)) {
            #$this->warn("⚠ Routes for '{$prefix}' already exist. Skipping.");
            return;
        }

        // Agregar si no existe
        File::append($mainRoutesPath, "\n{$routeLine}\n");

        $this->info("🔗 Routes '{$prefix}' linked in main routes/api.php");
    }
    private function createBaseFolders(string $base): void
    {
        $dirs = [
            # Domain Layer
            'Domain/Entities',
            'Domain/ValueObjects',
            'Domain/Contracts',
            'Domain/Events',

            # Application Layer
            'Application',

            # Infrastructure Layer
            'Infrastructure/Http/Controllers',
            'Infrastructure/Http/Requests',
            'Infrastructure/Http/Routes',

            'Infrastructure/Persistence/Eloquent/Models',
            'Infrastructure/Persistence/Eloquent/Mappers',
            'Infrastructure/Persistence/Eloquent/Repositories',

            'Infrastructure/Events',

            'Infrastructure/Providers',
        ];

        $this->createFolders($dirs, $base);
    }

    private function createBaseFiles(string $base, string $context, string $module): void
    {
        $files = [

            /************************************************/
            /* Domain Layer
            /************************************************/
            "Domain/Contracts/{$module}RepositoryInterface.php" => $this->repositoryContract($context, $module),

            "Domain/Entities/{$module}.php" => $this->entity($context, $module),

            "Domain/ValueObjects/{$module}Name.php" => $this->voName($context, $module),
            "Domain/ValueObjects/{$module}Description.php" => $this->voDescription($context, $module),
            "Domain/ValueObjects/{$module}Price.php" => $this->voPrice($context, $module),

            "Domain/Events/{$module}Created.php" => $this->createEvent($context, $module),

            /************************************************/
            /* Application Layer
            /************************************************/

            "Application/Create{$module}UseCase.php" => $this->applicationUseCase($context, $module),


            /************************************************/
            /* Infrastructure Layer
            /************************************************/
            "Infrastructure/Http/Controllers/Create{$module}PostController.php" => $this->InfrastructureController($context, $module),
            "Infrastructure/Http/Requests/Create{$module}Request.php" => $this->InterfaceRequest($context, $module),
            "Infrastructure/Http/Routes/api.php" => $this->routes($context, $module),

            "Infrastructure/Persistence/Eloquent/Models/{$module}Model.php" => $this->eloquentModel($context, $module),
            "Infrastructure/Persistence/Eloquent/Mappers/{$module}Mapper.php" => $this->eloquentMapper($context, $module),
            "Infrastructure/Persistence/Eloquent/Repositories/{$module}Repository.php" => $this->eloquentRepo($context, $module),

            "Infrastructure/Events/DomainEventDispatcher.php" => $this->domainEventDispatcher($context, $module),

            "Infrastructure/Providers/PersistenceServiceProvider.php" => $this->PersistenceProvider($context, $module),
        ];

        if ($this->isRedis || $this->isRabbitMQ) {
            $files["Infrastructure/Providers/EventServiceProvider.php"] = $this->EventServiceProvider($context, $module);
        }

        $this->createFiles($files, $base);
    }

    /* -------------------------------- Additional Complements -------------------------------- */

    private function createRedis(string $base, string $context, string $module): void
    {
        $dirs = [
            'Infrastructure/Cache/Contracts',
            'Infrastructure/Cache/Redis',
            'Infrastructure/Cache/Decorators',
            'Infrastructure/Cache/Listeners',
        ];

        $files = [
            "Infrastructure/Cache/Contracts/CacheStoreInterface.php" => $this->redisContracts($context, $module),
            "Infrastructure/Cache/Redis/RedisCacheStore.php" => $this->redisCache($context, $module),
            "Infrastructure/Cache/Decorators/Cached{$module}Repository.php" => $this->redisRepository($context, $module),

            "Infrastructure/Cache/Listeners/{$module}CreatedListener.php" => $this->RedisListener($context, $module),


            "Infrastructure/Providers/CacheServiceProvider.php" => $this->cacheServiceProvider($context, $module),
            "Infrastructure/Providers/CacheDecoratorProvider.php" => $this->cacheDecoratorProvider($context, $module),
        ];

        $this->createFolders($dirs, $base);
        $this->createFiles($files, $base);
    }


    private function createRabbitMQ(string $base, string $context, string $module): void
    {
        $dirs = [

            'Infrastructure/Messaging/Contracts',
            'Infrastructure/Messaging/RabbitMQ',
            'Infrastructure/Messaging/Mappers',
            'Infrastructure/Messaging/Listeners',
        ];

        $files = [
            "Infrastructure/Messaging/Contracts/EventPublisherInterface.php" => $this->rabbitMQContracts($context, $module),
            "Infrastructure/Messaging/RabbitMQ/EventPublisher.php" => $this->rabbitMQPublisher($context, $module),
            "Infrastructure/Messaging/Mappers/DomainEventToMessageMapper.php" => $this->rabbitMQMapper($context, $module),
            "Infrastructure/Messaging/RabbitMQ/EventConnection.php" => $this->eventConnection($context, $module),

            "Infrastructure/Messaging/Listeners/{$module}CreatedListener.php" => $this->rabbitMQListener($context, $module),

            "Infrastructure/Providers/MessagingServiceProvider.php" => $this->rabbitmqProvider($context, $module),
        ];

        $this->createFolders($dirs, $base);
        $this->createFiles($files, $base);
    }

    /* -------------------------------- STUBS -------------------------------- */

    /************************************************/
    /* Domain Layer
    /************************************************/

    private function repositoryContract($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Domain\\Contracts;

            use Src\\{$c}\\{$m}\\Domain\\Entities\\{$m};

            interface {$m}RepositoryInterface
            {
                public function save({$m} \$course): void;
                public function findById(int \$id): ?{$m};
                public function findAll(): array;
                public function delete(int \$id): void;
            }
            PHP;
    }

    private function entity($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Domain\\Entities;

            use Src\\{$c}\\{$m}\\Domain\\ValueObjects\\{$m}Name;
            use Src\\{$c}\\{$m}\\Domain\\ValueObjects\\{$m}Description;
            use Src\\{$c}\\{$m}\\Domain\\ValueObjects\\{$m}Price;

            final class {$m}
            {
                public function __construct(
                    private int \$id,
                    private {$m}Name \$name,
                    private {$m}Description \$description,
                    private {$m}Price \$price
                ) {}

                public function id(): ?int { return \$this->id; }

                public function setId(int \$id): void { \$this->id = \$id; }

                public function name(): {$m}Name { return \$this->name; }
                public function description(): {$m}Description { return \$this->description; }
                public function price(): {$m}Price { return \$this->price; }
            }
            PHP;
    }

    private function voName($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Domain\\ValueObjects;

            final class {$m}Name
            {
                public function __construct(private string \$value)
                {
                    if (strlen(\$value) < 3) {
                        throw new \\InvalidArgumentException('Invalid name');
                    }
                }

                public function value(): string { return \$this->value; }
            }
            PHP;
    }

    private function voDescription($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Domain\\ValueObjects;

            final class {$m}Description
            {
                public function __construct(private string \$value)
                {
                    if (\$value === '') {
                        throw new \\InvalidArgumentException('Description required');
                    }
                }

                public function value(): string { return \$this->value; }
            }
            PHP;
    }

    private function voPrice($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Domain\\ValueObjects;

            final class {$m}Price
            {
                public function __construct(private float \$value)
                {
                    if (\$value <= 0) {
                        throw new \\InvalidArgumentException('Invalid price');
                    }
                }

                public function value(): float { return \$this->value; }
            }
            PHP;
    }

    private function createEvent($c, $m): string
    {
        return <<<PHP
            <?php
            namespace Src\\{$c}\\{$m}\\Domain\\Events;

            use Src\\{$c}\\{$m}\\Domain\\Entities\\{$m};

            final class {$m}Created
            {
                public function __construct(public readonly {$m} \${$m}) {}
            }
            PHP;
    }

    /************************************************/
    /* Application Layer
    /************************************************/

    private function applicationUseCase($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Application;

            use Src\\{$c}\\{$m}\\Domain\\Contracts\\{$m}RepositoryInterface;
            use Src\\{$c}\\{$m}\\Domain\\Entities\\{$m};
            use Src\\{$c}\\{$m}\\Domain\\Events\\{$m}Created;
            use Src\\{$c}\\{$m}\\Domain\\ValueObjects\\{$m}Name;
            use Src\\{$c}\\{$m}\\Domain\\ValueObjects\\{$m}Description;
            use Src\\{$c}\\{$m}\\Domain\\ValueObjects\\{$m}Price;

            final class Create{$m}UseCase
            {
                public function __construct(
                    private {$m}RepositoryInterface \$repository,
                    private ?DomainEventDispatcher \$dispatcher = null
                ) {}

                public function execute(int \$id, string \$name, string \$description, float \$price): {$m}
                {
                    \${$m} = new {$m}(
                        \$id,
                        new {$m}Name(\$name),
                        new {$m}Description(\$description),
                        new {$m}Price(\$price)
                    );

                    \$this->repository->save(\${$m});

                    // Disparamos el evento de dominio
                    if (\$this->dispatcher) {
                        \$this->dispatcher->dispatch(new {$m}Created(\${$m}));
                    }

                    return \${$m};
                }
            }
            PHP;
    }






    /************************************************/
    /* Infrastructure Layer
    /************************************************/
    private function InfrastructureController($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Http\\Controllers;

            use App\\Http\\Controllers\\Controller;
            use Src\\{$c}\\{$m}\\Application\\Create{$m}UseCase;
            use Src\\{$c}\\{$m}\\Infrastructure\\Http\\Requests\\Create{$m}Request;

            final class Create{$m}PostController extends Controller
            {
                public function __invoke(Create{$m}Request \$request, Create{$m}UseCase \$useCase)
                {

                    \${$m} = \$useCase->execute(
                        id: \$request->id,
                        name: \$request->name,
                        description: \$request->description,
                        price: \$request->price
                    );

                    return response()->json(['id' => \${$m}->id()], 201);
                }
            }
            PHP;
    }

    private function InterfaceRequest($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Http\\Requests;

            use Illuminate\\Foundation\\Http\\FormRequest;

            final class Create{$m}Request extends FormRequest
            {
                public function authorize(): bool
                {
                    return true;
                }

                public function rules(): array
                {
                    return [
                        'name' => 'required|string|min:3',
                        'description' => 'required|string',
                        'price' => 'required|numeric|min:0.01',
                    ];
                }
            }
            PHP;
    }


    # Routes
    private function routes($c, $m): string
    {
        # Si se trabaja con otro middleware cambiarlo, por defecto estara con JWT, y si tiene autenticacion "['role:admin']"
        return <<<PHP
            <?php
            use Src\\{$c}\\{$m}\\Infrastructure\\Http\\Controllers\\Create{$m}PostController;

            Route::middleware(['auth:api'])->group(function () {
                Route::post('/create', [Create{$m}PostController::class, '__invoke']);
            });
            PHP;
    }



    # Eloquent

    private function eloquentModel($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Persistence\\Eloquent\\Models;

            use Illuminate\\Database\\Eloquent\\Model;

            final class {$m}Model extends Model
            {
                protected \$table = '{$m}';

                protected \$fillable = [
                    'id',
                    'name',
                    'description',
                    'price',
                ];
            }
            PHP;
    }

    private function eloquentMapper($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Persistence\\Eloquent\\Mapper;
            use Src\\{$c}\\{$m}\\Infrastructure\\Persistence\\Eloquent\\Models\\{$m}Model;
            use Src\\{$c}\\{$m}\\Domain\\Entities\\{$m};
            use Src\\{$c}\\{$m}\\Domain\\ValueObjects\\{$m}Name;
            use Src\\{$c}\\{$m}\\Domain\\ValueObjects\\{$m}Description;
            use Src\\{$c}\\{$m}\\Domain\\ValueObjects\\{$m}Price;

            final class {$m}Mapper
            {
                public static function toEloquent({$m} \${$m}): array
                {
                    return [
                        'id' => \${$m}->id(),
                        'name' => \${$m}->name()->value(),
                        'description' => \${$m}->description()->value(),
                        'price' => \${$m}->price()->value(),
                    ];
                }

                public static function toDomain({$m}Model \$model): {$m}
                {
                    return new {$m}(
                        \$model->id,
                        new {$m}Name(\$model->name),
                        new {$m}Description(\$model->description),
                        new {$m}Price(\$model->price),
                    );
                }
            }
            PHP;
    }

    private function eloquentRepo($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Persistence\\Eloquent\\Repositories;

            use Src\\{$c}\\{$m}\\Domain\\Contracts\\{$m}RepositoryInterface;
            use Src\\{$c}\\{$m}\\Infrastructure\\Persistence\\Eloquent\\Models\\{$m}Model;
            use Src\\{$c}\\{$m}\\Infrastructure\\Persistence\\Eloquent\\Mapper\\{$m}Mapper;
            use Src\\{$c}\\{$m}\\Domain\\Entities\\{$m};

            final class {$m}Repository implements {$m}RepositoryInterface
            {
                public function save({$m} \${$m}): void
                {
                    \$model = {$m}Model::updateOrCreate(
                        ['id' => \${$m}->id()],
                        {$m}Mapper::toEloquent(\${$m})
                    );

                    if (\${$m}->id() === null) {
                        \${$m}->setId(\$model->id);
                    }
                }

                public function findById(int \$id): ?{$m}
                {
                    \$model = {$m}Model::find(\$id);

                    return \$model
                        ? {$m}Mapper::toDomain(\$model)
                        : null;
                }

                public function findAll(): array {}
                public function delete(int \$id): void {}

            }
            PHP;
    }



    # Disparador de Eventos
    private function domainEventDispatcher($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Events;

            final class DomainEventDispatcher
            {
                private array \$listeners = [];

                public function register(string \$eventClass, callable \$listener): void
                {
                    \$this->listeners[\$eventClass][] = \$listener;
                }

                public function dispatch(object \$event): void
                {
                    \$eventClass = get_class(\$event);
                    foreach (\$this->listeners[\$eventClass] ?? [] as \$listener) {
                        \$listener(\$event);
                    }
                }
            }
            PHP;
    }






    # Redis
    private function redisContracts($c, $m): string
    {
        return <<<PHP
            <?php
            namespace Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Contracts;

            interface CacheStoreInterface
            {
                /**
                 * Obtiene un valor desde cache
                 */
                public function get(string \$key): mixed;

                /**
                 * Guarda un valor en cache por N segundos
                 */
                public function put(string \$key, mixed \$value, int \$ttl): void;

                /**
                 * Obtiene o guarda el valor si no existe
                 */
                public function remember(string \$key, int \$ttl, callable \$callback): mixed;

                /**
                 * Elimina una clave
                 */
                public function forget(string \$key): void;
            }

            PHP;
    }

    private function redisCache($c, $m): string
    {
        return <<<PHP
            <?php
            namespace Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Redis;

            use Illuminate\\Support\\Facades\\Cache;
            use Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Contracts\\CacheStoreInterface;

            final class RedisCacheStore implements CacheStoreInterface
            {
                public function get(string \$key): mixed
                {
                    return Cache::store('redis')->get(\$key);
                }

                public function put(string \$key, mixed \$value, int \$ttl): void
                {
                    Cache::store('redis')->put(\$key, \$value, \$ttl);
                }

                public function remember(string \$key, int \$ttl, callable \$callback): mixed
                {
                    return Cache::store('redis')->remember(\$key, \$ttl, \$callback);
                }

                public function forget(string \$key): void
                {
                    Cache::store('redis')->forget(\$key);
                }
            }
            PHP;
    }

    private function redisListener($c, $m): string
    {
        return <<<PHP
            <?php
            namespace Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Listeners;

            use Src\\{$c}\\{$m}\\Domain\\Events\\{$m}Created;
            use Src\\{$c}\\{$m}\\Domain\\Contracts\\{$m}RepositoryInterface;
            use Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Contracts\\CacheStoreInterface;

            final class {$m}CreatedListener
            {
                public function __construct(
                    private {$m}RepositoryInterface \$repository,
                    private CacheStoreInterface \$cache
                ) {}

                public function handle({$m}Created \$event): void
                {
                    // Actualiza la cache
                    \$venues = \$this->repository->findAll();
                    \$this->cache->put('{$m}.all', \$venues, 300);
                }
            }
            PHP;
    }

    private function redisRepository($c, $m): string
    {
        return <<<PHP
            <?php
            namespace Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Decorators;

            use Src\\{$c}\\{$m}\\Domain\\Contracts\\{$m}RepositoryInterface;
            use Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Contracts\\CacheStoreInterface;
            use Src\\{$c}\\{$m}\\Domain\\Entities\\{$m};

            final class Cached{$m}Repository implements {$m}RepositoryInterface
            {
                public function __construct(
                    private {$m}RepositoryInterface \$repository,
                    private CacheStoreInterface \$cache
                ) {}

                public function findAll(): array
                {
                    return \$this->cache->remember(
                        '{$m}.all',
                        300,
                        fn () => \$this->repository->findAll()
                    );
                }

                public function save({$m} \${$m}): void {}
                public function delete(int \$id): void {}
                public function findById(int \$id): ?{$m} {}

                
            }
            PHP;
    }

    private function cacheServiceProvider($c, $m): string
    {
        return <<<PHP
            <?php
            namespace Src\\{$c}\\{$m}\\Infrastructure\\Providers;

            use Illuminate\\Support\\ServiceProvider;
            use Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Contracts\\CacheStoreInterface;
            use Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Redis\\RedisCacheStore;

            final class CacheServiceProvider extends ServiceProvider
            {
                public function register(): void
                {
                    \$this->app->singleton(
                        CacheStoreInterface::class,
                        RedisCacheStore::class
                    );
                }
            }
            PHP;
    }

    private function cacheDecoratorProvider($c, $m): string
    {
        return <<<PHP
            <?php
            namespace Src\\{$c}\\{$m}\\Infrastructure\\Providers;

            use Illuminate\\Support\\ServiceProvider;
            use Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Contracts\\CacheStoreInterface;
            use Src\\{$c}\\{$m}\\Domain\\Contracts\\{$m}RepositoryInterface;
            use Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Decorators\\Cached{$m}Repository;

            final class CacheDecoratorProvider extends ServiceProvider
            {
                public function register(): void
                {
                    if (! \$this->app->bound(CacheStoreInterface::class)) {
                        return;
                    }

                    \$this->app->extend(
                        {$m}RepositoryInterface::class,
                        fn (\$repo, \$app) =>
                            new Cached{$m}Repository(
                                \$repo,
                                \$app->make(CacheStoreInterface::class)
                            )
                    );
                }
            }
            PHP;
    }





    # RabbitMQ 
    private function rabbitMQContracts($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\Contracts;

            interface EventPublisherInterface
            {
                public function publish(object \$event): void;
            }
            PHP;
    }
    private function rabbitMQPublisher($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\RabbitMQ;

            use PhpAmqpLib\\Message\\AMQPMessage;
            use PhpAmqpLib\\Channel\\AMQPChannel;

            use Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\Contracts\\EventPublisherInterface;
            use Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\Mapper\\DomainEventToMessageMapper;
            use Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\RabbitMQ\\EventConnection;

            final class EventPublisher implements EventPublisherInterface
            {
                private AMQPChannel \$channel;

                public function __construct(
                    EventConnection \$connection
                ) {
                    // 🔥 Se crea UNA sola conexión
                    \$conn = \$connection->create();

                    // 🔥 Se reutiliza el canal
                    \$this->channel = \$conn->channel();

                    // (opcional) declarar exchange una vez
                    \$this->channel->exchange_declare(
                        'domain.events',
                        'topic',
                        false,
                        true,
                        false
                    );
                }

                public function publish(object \$event): void
                {
                    \$data = DomainEventToMessageMapper::map(\$event);

                    \$message = new AMQPMessage(
                        json_encode(\$data),
                        [
                            'content_type' => 'application/json',
                            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                        ]
                    );

                    \$this->channel->basic_publish(
                        \$message,
                        'domain.events',
                        class_basename(\$event)
                    );
                }
            }
            PHP;
    }
    private function rabbitMQMapper($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\Mapper;

            final class DomainEventToMessageMapper
            {
                public static function map(object \$event): array
                {
                    return [
                        'event' => class_basename(\$event),
                        'payload' => get_object_vars(\$event),
                        'occurred_at' => now()->toISOString(),
                    ];
                }
            }
            PHP;
    }
    private function eventConnection($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\RabbitMQ;

            use PhpAmqpLib\\Connection\\AMQPStreamConnection;

            final class EventConnection
            {
                public function create(): AMQPStreamConnection
                {
                    return new AMQPStreamConnection(
                        config('rabbitmq.host'),
                        config('rabbitmq.port'),
                        config('rabbitmq.user'),
                        config('rabbitmq.password'),
                        config('rabbitmq.vhost')
                    );
                }
            }
            PHP;
    }

    private function rabbitMQListener($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\Listeners;

            use Src\\{$c}\\{$m}\\Domain\\Events\\{$m}Created;
            use Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\Contracts\\EventPublisherInterface;

            final class {$m}CreatedListener
            {
                public function __construct(
                    private EventPublisherInterface \$publisher
                ) {}

                public function handle({$m}Created \$event): void
                {
                    \$this->publisher->publish(\$event);
                }
            }
            PHP;
    }

    private function rabbitMQProvider($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Providers;

            use Illuminate\\Support\\ServiceProvider;
            use Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\Contracts\\EventPublisherInterface;
            use Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\RabbitMQ\\EventPublisher;

            final class MessagingServiceProvider extends ServiceProvider
            {
                public function register(): void
                {
                    \$this->app->bind(
                        EventPublisherInterface::class,
                        EventPublisher::class
                    );
                }
            }
            PHP;
    }





    # Providers
    private function PersistenceProvider($c, $m): string
    {
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Providers;

            use Illuminate\\Support\\ServiceProvider;
            use Src\\{$c}\\{$m}\\Domain\\Contracts\\{$m}RepositoryInterface;
            use Src\\{$c}\\{$m}\\Infrastructure\\Persistence\\Eloquent\\Repositories\\{$m}Repository;

            final class PersistenceServiceProvider extends ServiceProvider
            {
                public function register(): void
                {
                    \$this->app->bind(
                        {$m}RepositoryInterface::class,
                        {$m}Repository::class
                    );
                }
            }
            PHP;
    }

    private function EventServiceProvider($c, $m): string
    {
        $redis = $this->isRedis ? "use Src\\{$c}\\{$m}\\Infrastructure\\Cache\\Listeners\\{$m}CreatedListener as RedisListener;" : "";
        $rabbit = $this->isRabbitMQ ? "use Src\\{$c}\\{$m}\\Infrastructure\\Messaging\\Listeners\\{$m}CreatedListener as RabbitListener;" : "";

        $redisListeners = $this->isRedis ? "\$dispatcher->register({$m}Created::class, fn(\$event) => app(RedisListener::class)->handle(\$event));" : "";
        $rabbitListeners = $this->isRabbitMQ ? "\$dispatcher->register({$m}Created::class, fn(\$event) => app(RabbitListener::class)->handle(\$event));" : "";
        return <<<PHP
            <?php

            namespace Src\\{$c}\\{$m}\\Infrastructure\\Providers;

            use Illuminate\Support\ServiceProvider;
            use Src\\{$c}\\{$m}\\Infrastructure\\Events\\DomainEventDispatcher;
            use Src\\{$c}\\{$m}\\Domain\\Events\\{$m}Created;
            {$redis}
            {$rabbit}

            final class EventServiceProvider extends ServiceProvider
            {
                public function boot(DomainEventDispatcher \$dispatcher): void
                {
                    {$redisListeners}
                    {$rabbitListeners}
                }
            }
            PHP;
    }
}

