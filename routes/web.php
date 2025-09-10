<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $users = User::all();
    dd($users);
});

Route::controller(MainController::class)->group(function(){
    Route::get('/login', 'loginPage')->name('login');
    Route::get('/login/{id}', 'loginSubmit')->name('login.submit');
    Route::get('/logout', 'logout')->name('logout');

});
