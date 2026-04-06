<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RiwayatPembayaranController;

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
        Route::delete('tagihan/{id}', [App\Http\Controllers\Admin\TagihanController::class, 'destroy'])->name('tagihan.destroy'); 

        Route::get('/riwayat-pembayaran', [RiwayatPembayaranController::class,'index'])
            ->name('riwayat.index');
        
    });



Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // ... route lainnya
        
        // Route untuk wali murid
        Route::resource('wali', App\Http\Controllers\WaliMuridController::class);
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

use App\Http\Controllers\Bendahara\DashboardController;
use App\Http\Controllers\Bendahara\SiswaController;
use App\Http\Controllers\Bendahara\TagihanController;

Route::middleware(['auth', 'role:bendahara'])
    ->prefix('bendahara')
    ->name('bendahara.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // CRUD siswa
        Route::resource('siswa', SiswaController::class);

        // Tagihan
        Route::get('/tagihan', [TagihanController::class,'index'])
            ->name('tagihan.index');

        Route::post('/tagihan/generate', [TagihanController::class,'generate'])
            ->name('tagihan.generate');

        Route::delete('/tagihan/{id}', [TagihanController::class,'destroy'])
            ->name('tagihan.destroy');


        Route::get('/riwayat-pembayaran', [RiwayatPembayaranController::class,'index'])
            ->name('riwayat.index');

        Route::post('/pembayaran/manual', [App\Http\Controllers\PembayaranController::class, 'storeManual'])
        ->name('pembayaran.manual.store');
});

/*
|--------------------------------------------------------------------------
| Wali Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Wali\TagihanController as WaliTagihanController;
use App\Http\Controllers\Wali\DashboardController as WaliDashboardController;
use App\Http\Controllers\Wali\RiwayatPembayaranController as WaliRiwayatController;
use App\Http\Controllers\Wali\NotifikasiController;

Route::middleware(['auth', 'role:wali'])
    ->prefix('wali')
    ->name('wali.')
    ->group(function () {

        Route::get('/dashboard', [WaliDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/tagihan', [WaliTagihanController::class, 'index'])
            ->name('tagihan.index');

        Route::get('/riwayat', [WaliRiwayatController::class, 'index'])
            ->name('riwayat.index');

        // NOTIFIKASI
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
            ->name('notifikasi.index');

        Route::get('/notifikasi/{id}/read', [NotifikasiController::class, 'read'])
            ->name('notifikasi.read');
    });

require __DIR__.'/auth.php';


Route::post('/wali/tagihan/{id}/bayar', [PembayaranController::class, 'bayar'])
    ->name('wali.tagihan.bayar');

Route::post('/midtrans/callback', [App\Http\Controllers\MidtransCallbackController::class, 'handle'])
    ->name('midtrans.callback');

use App\Http\Controllers\Wali\BuktiPembayaranController;

Route::get('/wali/bukti-pembayaran', [BuktiPembayaranController::class,'download'])
    ->name('wali.bukti.pembayaran');

// Route::get('/riwayat', [App\Http\Controllers\Wali\RiwayatPembayaranController::class, 'index'])
//     ->name('riwayat.index');

Route::get('/bukti-pembayaran/{filename}', function ($filename) {
    $path = storage_path('app/public/bukti-pembayaran/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $filename . '"'
    ]);
})->name('bukti.pembayaran')->middleware(['auth']); // Tambah middleware auth