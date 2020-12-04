<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/backend/user-management/user', \App\Http\Livewire\Backend\UserManagement\UserController::class)->name('user');
Route::get('/backend/user-management/invitation', \App\Http\Livewire\Backend\UserManagement\InvitationController::class)->name('invitation');