<?php

use Illuminate\Support\Facades\Route;
use Src\Catalog\Category\Infrastructure\Http\Controllers\CreateCategoryPostController;
use Src\Catalog\Category\Infrastructure\Http\Controllers\GetCategoryGetController;
use Src\Catalog\Category\Infrastructure\Http\Controllers\UpdateCategoryPutController;
use Src\Catalog\Category\Infrastructure\Http\Controllers\DeleteCategoryDeleteController;
use Src\Catalog\Category\Infrastructure\Http\Controllers\GetAllCategoryGetController;
use Src\Catalog\Category\Infrastructure\Http\Controllers\GetCategoryWithProductsController;

Route::post('/', CreateCategoryPostController::class);
Route::get('/', GetAllCategoryGetController::class);
Route::get('/{id}', GetCategoryGetController::class);
Route::put('/{id}', UpdateCategoryPutController::class);
Route::delete('/{id}', DeleteCategoryDeleteController::class);
Route::get('/{id}/products', GetCategoryWithProductsController::class);