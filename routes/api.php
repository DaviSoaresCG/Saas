<?php

use App\Http\Controllers\API\SigaDezAPI;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/sync-products', [SigaDezAPI::class, 'syncProducts']);
    Route::get('/teste', fn() => "teste");
});


