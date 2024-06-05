<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RMController;
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

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login-proses', [LoginController::class, 'login_proses'])->name('login-proses');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/editprofile/{id}', [DashboardController::class, 'editprofile'])->name('editprofile');
    Route::put('/updateuser/{id}', [DashboardController::class, 'updateuser'])->name('updateuser');
    Route::get('/getchart', [DashboardController::class, 'getchart'])->name('getchart');

    Route::get('/usermanagement', [AdminController::class, 'usermanagement'])->name('usermanagement');
    Route::post('/insertuser', [AdminController::class, 'insertuser'])->name('insertuser');
    Route::put('/edituser/{id}', [AdminController::class, 'edituser'])->name('edituser');
    Route::delete('/deleteuser/{id}', [AdminController::class, 'deleteuser'])->name('deleteuser');

    Route::get('/doktermanagement', [AdminController::class, 'doktermanagement'])->name('doktermanagement');
    Route::post('/insertdokter', [AdminController::class, 'insertdokter'])->name('insertdokter');
    Route::put('/editdokter/{id}', [AdminController::class, 'editdokter'])->name('editdokter');
    Route::delete('/deletedokter/{id}', [AdminController::class, 'deletedokter'])->name('deletedokter');

    Route::get('/formulirmanagement', [FormulirController::class, 'formulirmanagement'])->name('formulirmanagement');
    Route::post('/insertformulir', [FormulirController::class, 'insertformulir'])->name('insertformulir');
    Route::put('/updateformulir/{id}', [FormulirController::class, 'updateformulir'])->name('updateformulir');
    Route::delete('/deleteformulir/{id}', [FormulirController::class, 'deleteformulir'])->name('deleteformulir');
    Route::get('/createisi/{id}', [FormulirController::class, 'createisi'])->name('createisi');
    Route::post('/insertisi', [FormulirController::class, 'insertisi'])->name('insertisi');
    Route::put('/updateisi/{id}', [FormulirController::class, 'updateisi'])->name('updateisi');
    Route::get('/deleteisi/{id}', [FormulirController::class, 'deleteisi'])->name('deleteisi');

    Route::get('/pasienmanagement', [RMController::class, 'pasienmanagement'])->name('pasienmanagement');
    Route::post('/insertpasien', [RMController::class, 'insertpasien'])->name('insertpasien');
    Route::get('/editpasien/{id}', [RMController::class, 'editpasien'])->name('editpasien');
    Route::put('/updatepasien/{id}', [RMController::class, 'updatepasien'])->name('updatepasien');
    Route::delete('/deletepasien/{id}', [RMController::class, 'deletepasien'])->name('deletepasien');

    Route::get('/analisismanagement', [RMController::class, 'analisismanagement'])->name('analisismanagement');
    Route::get('/analisisbaru/{analisis_id}', [RMController::class, 'analisisbaru'])->name('analisisbaru');
    Route::get('/analisislama/{id}', [RMController::class, 'analisislama'])->name('analisislama');
    Route::post('/insertawal', [RMController::class, 'insertawal'])->name('insertawal');
    Route::post('/insertform', [RMController::class, 'insertform'])->name('insertform');
    Route::get('/analisiskualitatif/{analisis_id}', [RMController::class, 'analisiskualitatif'])->name('analisiskualitatif');
    Route::post('/insertkualitatif', [RMController::class, 'insertkualitatif'])->name('insertkualitatif');
    Route::get('/hasil/{analisis_id}', [RMController::class, 'hasil'])->name('hasil');
    Route::get('pdf/{analisis_id}', [RMController::class, 'pdf'])->name('hasil.pdf');
    Route::get('/editkuantitatif/{analisis_id}', [RMController::class, 'editkuantitatif'])->name('editkuantitatif');
    Route::put('/updateform', [RMController::class, 'updateform'])->name('updateform');
    Route::get('/editkualitatif/{analisis_id}', [RMController::class, 'editkualitatif'])->name('editkualitatif');
    Route::put('/updatekualitatif', [RMController::class, 'updatekualitatif'])->name('updatekualitatif');

    Route::get('/viewklpcm', [DokterController::class, 'viewklpcm'])->name('viewklpcm');
    Route::get('/ketidaklengkapan', [DokterController::class, 'ketidaklengkapan'])->name('ketidaklengkapan');

    Route::get('/laporanmanagement', [LaporanController::class, 'laporanmanagement'])->name('laporanmanagement');
    Route::get('/laporanformulir', [LaporanController::class, 'laporanformulir'])->name('laporanformulir');
    Route::get('/laporankualitatif', [LaporanController::class, 'laporankualitatif'])->name('laporankualitatif');
    Route::get('/laporangrafik/pdf', [LaporanController::class, 'laporangrafikPDF'])->name('laporangrafikPDF');
    Route::get('/laporangrafik', [LaporanController::class, 'laporangrafik'])->name('laporangrafik');
    Route::get('/laporan/filter', [LaporanController::class, 'laporanmanagement'])->name('laporanfilter');
    Route::get('/laporan/pdf', [LaporanController::class, 'downloadPDF'])->name('laporanpdf');
    Route::get('/laporan/excel', [LaporanController::class, 'downloadExcel'])->name('laporanexcel');
    Route::get('/getchart', [LaporanController::class, 'getchart'])->name('getchart');
});
// NOTIFICATION READ
Route::get('/notification', [NotificationController::class, 'markRead'])->name('notifications.markAsRead');
// Update user
