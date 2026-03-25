<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

Route::get('/', function(){
    return view('admin.dashboard');
})->name('dashboard');

Route::resource('roles', RoleController::class);

});