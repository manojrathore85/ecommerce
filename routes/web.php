<?php

use App\Events\UserEmailverified;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FloreController;
use App\Http\Controllers\FlateController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VoucherController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\AuthenticateSession;
use Laravel\Ui\Presets\React;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\Artisan;

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
    Route::get('/createdumyuser', [UserController::class, 'create10user']);
});
Route::resource('group', GroupController::class);
Route::resource('account', AccountController::class);
Route::resource('flore', FloreController::class);
Route::resource('flate', FlateController::class);

Route::get('flate/{id}/{copy}', [FlateController::class, 'edit']);
//Voucher Route
Route::controller(VoucherController::class)->group(function(){
    Route::get('/voucher','index');
    Route::post('/voucher','store');
    Route::get('/voucher/create','create');
    Route::get('/voucher/{id}/edit','edit');
    Route::post('/voucher/{id}/update','update');
    Route::post('/voucher/delete/', 'destroy');
    Route::get('/voucherdetail/{id}', 'voucherDetail');
});
Route::controller(ReportController::class)->group(function (){
    Route::get('report/journal', 'journal');
    Route::get('report/flate-ledger', 'flateLedgerView');
    Route::get('report/account-ledger', 'accountLedgerView');
    Route::post('report/flate-ledger', 'flateLedger');
    Route::post('report/account-ledger', 'accountLedger');
});

//to user artisan commands
Route::get('/phpartisan-migrate', function () {
    $exitCode = Artisan::call('migrate:refresh', [
        '--force' => true,
    ]);
    //
});
