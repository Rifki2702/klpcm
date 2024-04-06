<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RMController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware;


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

Route::get('/login',[LoginController::class,'index'])->name('login'); 
Route::post('/login-proses',[LoginController::class,'login_proses'])->name('login-proses');
Route::get('/logout',[LoginController::class,'logout'])->name('logout'); 

Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'] , function(){
    Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('dashboard');
    Route::get('/editprofile/{id}',[DashboardController::class,'editprofile'])->name('editprofile');
    Route::put('/updateprofile/{id}',[DashboardController::class,'updateprofile'])->name('updateprofile');
    
    Route::get('/usermanagement',[AdminController::class,'usermanagement'])->name('usermanagement');
    Route::get('/createuser',[AdminController::class,'createuser'])->name('createuser');
    Route::post('/insertuser',[AdminController::class,'insertuser'])->name('insertuser');
    Route::get('/edituser/{id}',[AdminController::class,'edituser'])->name('edituser');
    Route::put('/updateuser/{id}',[AdminController::class,'updateuser'])->name('updateuser');
    Route::delete('/deleteuser/{id}',[AdminController::class,'deleteuser'])->name('deleteuser');

    Route::get('/formulirmanagement',[FormulirController::class,'formulirmanagement'])->name('formulirmanagement');
    Route::get('/createformulir',[FormulirController::class,'createformulir'])->name('createformulir');
    Route::post('/insertformulir',[FormulirController::class,'insertformulir'])->name('insertformulir');
    Route::delete('/deleteformulir/{id}', [FormulirController::class, 'deleteformulir'])->name('deleteformulir');
    Route::get('/createisi/{id}',[FormulirController::class,'createisi'])->name('createisi');
    Route::post('/insertisi',[FormulirController::class,'insertisi'])->name('insertisi');
    Route::get('/deleteisi/{id}', [FormulirController::class, 'deleteisi'])->name('deleteisi');

    Route::get('/pasienmanagement',[RMController::class,'pasienmanagement'])->name('pasienmanagement');
    Route::get('/createpasien',[RMController::class,'createpasien'])->name('createpasien');
    Route::post('/insertpasien',[RMController::class,'insertpasien'])->name('insertpasien');
    Route::get('/editpasien/{id}',[RMController::class,'editpasien'])->name('editpasien');
    Route::put('/updatepasien/{id}',[RMController::class,'updatepasien'])->name('updatepasien');
    Route::delete('/deletepasien/{id}',[RMController::class,'deletepasien'])->name('deletepasien');
    
    Route::get('/analisismanagement',[RMController::class,'analisismanagement'])->name('analisismanagement');
    Route::get('/analisisbaru/{id}',[RMController::class,'analisisbaru'])->name('analisisbaru');
    Route::get('/analisislama/{id}',[RMController::class,'analisislama'])->name('analisislama');

    Route::get('/viewklpcm',[DokterController::class,'viewklpcm'])->name('viewklpcm');

    Route::get('/laporanmanagement',[LaporanController::class,'laporanmanagement'])->name('laporanmanagement');
});