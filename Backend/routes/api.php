<?php

use Illuminate\Support\Facades\Route;


Route::prefix('Auth/User')->group(base_path('src/Auth/User/Infrastructure/Http/Routes/api.php'));

Route::prefix('Auth/Client')->group(base_path('src/Auth/Client/Infrastructure/Http/Routes/api.php'));

Route::prefix('Catalog/Product')->group(base_path('src/Catalog/Product/Infrastructure/Http/Routes/api.php'));
Route::prefix('Catalog/Category')->group(base_path('src/Catalog/Category/Infrastructure/Http/Routes/api.php'));


Route::prefix('Catalog/ProductImage')->group(base_path('src/Catalog/ProductImage/Infrastructure/Http/Routes/api.php'));

Route::prefix('Customer/Profile')->group(base_path('src/Customer/Profile/Infrastructure/Http/Routes/api.php'));

Route::prefix('Customer/Address')->group(base_path('src/Customer/Address/Infrastructure/Http/Routes/api.php'));

Route::prefix('Cart/Cart')->group(base_path('src/Cart/Cart/Infrastructure/Http/Routes/api.php'));

Route::prefix('Cart/CartItem')->group(base_path('src/Cart/CartItem/Infrastructure/Http/Routes/api.php'));

Route::prefix('Order/Order')->group(base_path('src/Order/Order/Infrastructure/Http/Routes/api.php'));

Route::prefix('Order/OrderItem')->group(base_path('src/Order/OrderItem/Infrastructure/Http/Routes/api.php'));

Route::prefix('Payment/Payment')->group(base_path('src/Payment/Payment/Infrastructure/Http/Routes/api.php'));

Route::prefix('Shipping/Shipment')->group(base_path('src/Shipping/Shipment/Infrastructure/Http/Routes/api.php'));

Route::prefix('Review/ProductReview')->group(base_path('src/Review/ProductReview/Infrastructure/Http/Routes/api.php'));

Route::prefix('AdminLog/Log')->group(base_path('src/AdminLog/Log/Infrastructure/Http/Routes/api.php'));

