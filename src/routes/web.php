<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\AdminController;

/*|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware('auth')->group(function(){
    Route::get('/attendance',[GeneralController::class,'index']);

    

    Route::get('/attendance/list',[GeneralController::class,'list']);

    route::get('/attendance/{id}',[GeneralController::class, 'detail']);

    Route::get('/stamp_correction_request/list',[GeneralController::class,'request']);

    Route::post('/attendance',[GeneralController::class,'postrequest']);

    Route::post('/save-time',[GeneralController::class,'saveTime']);
    
});

Route::get('/admin/login',[AdminController::class,'login']);

Route::post('/admin/login',[AdminController::class,'store']);

Route::middleware(['auth','admin'])->group(function () {
    Route::get('/admin/attendance/list', [AdminController::class, 'index']);


});
