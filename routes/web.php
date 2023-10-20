<?php

use App\Events\UserEmailverified;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\AuthenticateSession;
use Laravel\Ui\Presets\React;
use App\Models\User;

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
Route::get('/login', [AuthController::class, 'loginview'])->name('loginview');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'registerview'])->name('registerview');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/piramid', [AuthController::class, 'piramid']);
Route::get('/email/resend/{id}', [AuthController::class, 'verificationResend'])->name('verification.resend');
Route::get('email/notice', [AuthController::class, 'verificationNotice'])->name('verification.notice');
Route::get('verify-email/{id}/{hash}', [AuthController::class, 'verificationVerify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    Route::get('/profile', function () {
        return view('auth.profile');
    });
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/users', [UserController::class, 'index']);

    Route::get('/user', [UserController::class, 'manage']);
    Route::post('/user', [UserController::class, 'store']);
    Route::get('/user/{id}', [UserController::class, 'manage']);
    Route::post('/user/{id}', [UserController::class, 'store']);
    Route::post('/user-delete',[UserController::class, 'delete']);
    Route::get('/getusers', [UserController::class, 'getusers']);
});
