<?php

use Illuminate\Support\Facades\Route;
use Src\Catalog\Product\Infrastructure\Http\Controllers\CreateProductPostController;
use Src\Catalog\Product\Infrastructure\Http\Controllers\GetProductGetController;
use Src\Catalog\Product\Infrastructure\Http\Controllers\UpdateProductPutController;
use Src\Catalog\Product\Infrastructure\Http\Controllers\DeleteProductDeleteController;
use Src\Catalog\Product\Infrastructure\Http\Controllers\GetAllProductsGetController;


Route::post('/', CreateProductPostController::class);
Route::get('/', GetAllProductsGetController::class);
Route::get('/{id}', GetProductGetController::class);
Route::put('/{id}', UpdateProductPutController::class);
Route::delete('/{id}', DeleteProductDeleteController::class);


/*
// 2. Rutas de escritura (Protegidas con JWT + Rol de Admin)
Route::middleware(['auth:api', 'ensure.admin'])->group(function () {
    Route::post('/', CreateProductPostController::class);
    Route::put('/{id}', UpdateProductPutController::class);
    Route::delete('/{id}', DeleteProductDeleteController::class);
});
*/