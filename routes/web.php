<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::middleware(['guest:siswa'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});

Route::middleware(['guest:user'])->group(function () {
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});

Route::middleware(['auth:siswa'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);


//presensi
Route::get('/presensi/create', [PresensiController::class, 'create']);
Route::post('/presensi/store', [PresensiController::class, 'store']);

//edit profile
Route::get('/editprofile', [PresensiController::class, 'editprofile']);
Route::post('/presensi/{nis}/updateprofile', [PresensiController::class, 'updateprofile']);

//histori
Route::get('/presensi/histori', [PresensiController::class, 'histori']);
Route::post('/gethistori', [PresensiController::class, 'gethistori']);

//izin
Route::get('/presensi/izin', [PresensiController::class, 'izin']);
Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);
});

Route::middleware(['auth:user'])->group(function () {
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);

    //siswa
    Route::get('/siswa', [SiswaController::class, 'index']);
    Route::post('/siswa/store', [SiswaController::class, 'store']);
    Route::post('/siswa/edit', [SiswaController::class, 'edit']);
    Route::post('/siswa/{nis}/update', [SiswaController::class, 'update']);
    Route::post('/siswa/{nis}/delete', [SiswaController::class, 'delete']);
    Route::get('/siswa/{nis}/resetpassword', [SiswaController::class, 'resetpassword']);

    //jurusan
    Route::get('/jurusan', [JurusanController::class, 'index']);

    //presensi
    Route::get('/presensi/monitoring',[PresensiController::class, 'monitoring']);
    Route::post('/getpresensi',[PresensiController::class, 'getpresensi']);
    Route::post('/tampilkanpeta',[PresensiController::class, 'tampilkanpeta']);
    Route::get('/presensi/laporan',[PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan',[PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap',[PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap',[PresensiController::class, 'cetakrekap']);
    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);
    Route::post('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{id}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);

    //konfigurasi
    Route::get('/konfigurasi/lokasisekolah', [KonfigurasiController::class, 'lokasisekolah']);
    Route::post('/konfigurasi/updatelokasisekolah', [KonfigurasiController::class, 'updatelokasisekolah']);
});
