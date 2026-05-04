<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZakatController;
use App\Http\Controllers\RiwayatController;

// AUTH
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// USER - home dan setting
Route::get('/home', [UserController::class, 'showHomePage'])
    ->name('home')
    ->middleware('auth');

Route::get('/profile', [UserController::class, 'showProfile'])
    ->name('profile')
    ->middleware('auth');

Route::get('/profile/update', [UserController::class, 'showUpdateProfileForm'])
    ->name('profile.update')
    ->middleware('auth');
    
Route::post('/profile/update/avatar', [UserController::class, 'updateAvatar'])
    ->name('profile.update.avatar')
    ->middleware('auth');

Route::post('/profile/update/info', [UserController::class, 'updateProfileInfo'])
    ->name('profile.update.info')
    ->middleware('auth');

Route::get('/profile/keamanan', [UserController::class, 'showKeamanan'])
    ->name('profile.keamanan')
    ->middleware('auth');

Route::post('/profile/keamanan/update-password', [UserController::class, 'updatePassword'])
    ->name('profile.update.password')
    ->middleware('auth');

// USER - zakat
Route::middleware(['auth'])->group(function () {
    Route::get('/zakat', [ZakatController::class, 'index'])->name('zakat.index');
    Route::get('/zakat/history', [ZakatController::class, 'history'])->name('zakat.history');
    Route::post('/zakat/create-transaction', [ZakatController::class, 'createTransaction'])->name('zakat.create');
});

// Webhook untuk Midtrans (tanpa auth)
Route::post('/midtrans/notification', [ZakatController::class, 'handleNotification'])->name('midtrans.notification');
Route::get('/zakat/finish', [ZakatController::class, 'finish'])->name('zakat.finish');
Route::get('/zakat/manual-update/{orderId}', [ZakatController::class, 'manualUpdate']);

// Riwayat donasi dan zakat
Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/filter', [RiwayatController::class, 'filter'])->name('riwayat.filter');
});