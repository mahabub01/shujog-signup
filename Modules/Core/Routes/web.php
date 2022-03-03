<?php
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Auth All Route have auth.php route file
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
*/

Route::resource('demo', '\Modules\Core\Http\Controllers\CoreController');


//Load Module Component
Route::resource('{module}/load-component','\Modules\Core\Http\Controllers\Settings\ModulecomponentController')->only('index');


Route::get('/login', [\Modules\Core\Http\Controllers\Auth\LoginController::class,'loginView'])->name('login');


Route::get('login', [\Modules\Core\Http\Controllers\Auth\LoginController::class,'loginView'])->name('core.login.loadview');





Route::post('login', [\Modules\Core\Http\Controllers\Auth\LoginController::class,'loginViewSubmit'])->name('core.login.submit');

/*****************************************
 * Auth Routes has this file
 ********************************************/

Route::middleware(['auth'])->group(__DIR__ .'/auth.php');

/*****************************************
 * Auth Routes has this file
 ********************************************/



Route::middleware('core_permission')->prefix('accounts')->group(function () {

    Route::get('country',function(){
        dd("hello......");
    });



});








