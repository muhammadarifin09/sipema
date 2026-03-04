<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard dengan Controller
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Users Management
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);

        // Tahun Ajaran Management
        Route::resource('tahun-ajaran', App\Http\Controllers\Admin\TahunAjaranController::class);

        // Kelas Management
        Route::resource('kelas', App\Http\Controllers\Admin\KelasController::class);

        //Data Siswa
        Route::resource('siswa', App\Http\Controllers\Admin\SiswaController::class);

        // Pengaturan SPP
        Route::resource('spp-setting', App\Http\Controllers\Admin\SppSettingController::class);

        // Tagihan
        Route::get('tagihan', [App\Http\Controllers\Admin\TagihanController::class, 'index'])->name('tagihan.index');
        Route::post('tagihan/generate', [App\Http\Controllers\Admin\TagihanController::class, 'generate'])->name('tagihan.generate');
    });

/*
|--------------------------------------------------------------------------
| Bendahara Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Bendahara Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:bendahara'])
    ->prefix('bendahara')
    ->name('bendahara.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Bendahara\DashboardController::class, 'index'])
            ->name('dashboard');

        // Data Siswa
        // Route::resource('siswa', App\Http\Controllers\Bendahara\SiswaController::class);
   

    });

/*
|--------------------------------------------------------------------------
| Wali Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Wali\TagihanController;

use App\Http\Controllers\Wali\DashboardController;

Route::middleware(['auth', 'role:wali'])
    ->prefix('wali')
    ->name('wali.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/tagihan', [TagihanController::class, 'index'])
            ->name('tagihan.index');

    });

require __DIR__.'/auth.php';