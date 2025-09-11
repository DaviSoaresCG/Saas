<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('plans');
});

Route::controller(MainController::class)->group(function(){

    Route::middleware('auth')->group(function(){
        Route::get('/logout', 'logout')->name('logout');
        Route::get('/plans', 'plans')->name('plans');
        Route::get('/plan_selected/{id}', 'planSelected')->name('plans.selected');
        
        Route::get('/subscription/success', 'subscriptionSuccess')->name('subscription.success');
    });

    Route::middleware('guest')->group(function(){
        Route::get('/login', 'loginPage')->name('login');
        Route::get('/login/{id}', 'loginSubmit')->name('login.submit');
    });
    
    
});
