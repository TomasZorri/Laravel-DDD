<?php

namespace App\Console\Commands\Generators;

class DomainGenerator extends BaseGenerator
{
    public function generate(string $basePath, string $context, string $module): void
    {
        $replacements = ['context' => $context, 'module' => $module];
        $files = [
            // Domain
            "Domain/Entities/{$module}.php" => "Domain/Entities/Product",
            "Domain/Contracts/{$module}RepositoryInterface.php" => "Domain/Contracts/ProductRepositoryInterface",
            "Domain/Contracts/EventBusInterface.php" => "Domain/Contracts/EventBusInterface",

            "Domain/Events/{$module}Created.php" => "Domain/Events/ProductCreated",
            "Domain/Events/{$module}Updated.php" => "Domain/Events/ProductUpdated",
            "Domain/Events/{$module}Deleted.php" => "Domain/Events/ProductDeleted",

            "Domain/ValueObjects/{$module}CategoryId.php" => "Domain/ValueObjects/ProductCategoryId",
            "Domain/ValueObjects/{$module}Description.php" => "Domain/ValueObjects/ProductDescription",
            "Domain/ValueObjects/{$module}State.php" => "Domain/ValueObjects/ProductState",
            "Domain/ValueObjects/{$module}Id.php" => "Domain/ValueObjects/ProductId",
            "Domain/ValueObjects/{$module}Name.php" => "Domain/ValueObjects/ProductName",
            "Domain/ValueObjects/{$module}Price.php" => "Domain/ValueObjects/ProductPrice",
            "Domain/ValueObjects/{$module}Sku.php" => "Domain/ValueObjects/ProductSku",
            "Domain/ValueObjects/{$module}Stock.php" => "Domain/ValueObjects/ProductStock",


            // Application
            "Application/Create/Create{$module}Command.php" => "Application/Create/CreateProductCommand",
            "Application/Create/Create{$module}UseCase.php" => "Application/Create/CreateProductUseCase",
            "Application/Update/Update{$module}Command.php" => "Application/Update/UpdateProductCommand",
            "Application/Update/Update{$module}UseCase.php" => "Application/Update/UpdateProductUseCase",
            "Application/Delete{$module}UseCase.php" => "Application/DeleteProductUseCase",
            "Application/GetAll{$module}UseCase.php" => "Application/GetAllProductsUseCase",
            "Application/Get{$module}UseCase.php" => "Application/GetProductUseCase",


            // Infrastructure
            "Infrastructure/Http/Controllers/Create{$module}PostController.php" => "Infrastructure/Http/Controllers/CreateProductPostController",
            "Infrastructure/Http/Controllers/Update{$module}PutController.php" => "Infrastructure/Http/Controllers/UpdateProductPutController",
            "Infrastructure/Http/Controllers/Delete{$module}DeleteController.php" => "Infrastructure/Http/Controllers/DeleteProductDeleteController",
            "Infrastructure/Http/Controllers/GetAll{$module}GetController.php" => "Infrastructure/Http/Controllers/GetAllProductsGetController",
            "Infrastructure/Http/Controllers/Get{$module}GetController.php" => "Infrastructure/Http/Controllers/GetProductGetController",

            "Infrastructure/Http/Requests/Create{$module}Request.php" => "Infrastructure/Http/Requests/CreateProductRequest",
            "Infrastructure/Http/Requests/Update{$module}Request.php" => "Infrastructure/Http/Requests/UpdateProductRequest",

            "Infrastructure/Http/Routes/api.php" => "Infrastructure/Http/Routes/api",


            "Infrastructure/Bus/LaravelEventBus.php" => "Infrastructure/Bus/LaravelEventBus",

            "Infrastructure/Persistence/Eloquent/Mappers/{$module}Mapper.php" => "Infrastructure/Persistence/Eloquent/Mappers/ProductMapper",
            "Infrastructure/Persistence/Eloquent/Models/{$module}Model.php" => "Infrastructure/Persistence/Eloquent/Models/ProductModel",
            "Infrastructure/Persistence/Eloquent/Repositories/{$module}Repository.php" => "Infrastructure/Persistence/Eloquent/Repositories/ProductRepository",

            "Infrastructure/Providers/PersistenceServiceProvider.php" => "Infrastructure/Providers/PersistenceServiceProvider",


            "Infrastructure/Http/Filters/{$module}QueryFilter.php" => "Infrastructure/Http/Filters/ProductQueryFilter",
        ];

        $this->generateBatch($basePath, $files, $replacements);
    }
}