<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
// use App\Models\user as ModelUser;
use App\Models\user;

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

Route::get('users', [UsersController::class, 'index']);

Route::post('users/getUsers',[UsersController::class,'getUsers'])->name('getUsers');

Route::get('users/show',[UsersController::class,'show'])->name('users.show');
Route::get('users/edit',[UsersController::class,'edit'])->name('users.edit');
