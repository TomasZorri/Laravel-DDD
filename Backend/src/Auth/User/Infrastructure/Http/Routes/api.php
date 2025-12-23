<?php
use Src\Auth\User\Infrastructure\Http\Controllers\CreateUserPostController;

Route::middleware(['auth:api'])->group(function () {
    Route::post('/create', [CreateUserPostController::class, '__invoke']);
});