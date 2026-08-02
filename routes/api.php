<?php

use App\Http\Controllers\API\SigaDezAPI;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return User::all();
})->middleware('auth:sanctum');

Route::post('/onboarding', [SigaDezAPI::class, 'onboarding']);

