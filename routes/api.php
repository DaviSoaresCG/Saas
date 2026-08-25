<?php

use App\Http\Controllers\API\SigaDezAPI;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/sync-products', [SigaDezAPI::class, 'syncProducts']);
    Route::post('/sync-orders', [SigaDezAPI::class, 'syncOrders']);
    Route::get('/teste', fn() => "teste");
});


