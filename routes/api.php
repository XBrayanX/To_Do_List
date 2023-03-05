<?php

use App\Http\Controllers\QuehaceresController;
use App\Http\Middleware\Validate_Quehaceres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('quehaceres')->group(function (){
    Route::get('/index', [QuehaceresController::class, 'index'])->name('quehaceres_index');
    Route::get('/show/{id}', [QuehaceresController::class, 'show'])->name('quehaceres_show');

    Route::post('/store', [QuehaceresController::class, 'store'])->name('quehaceres_store');

    Route::put('/update', [QuehaceresController::class, 'update'])->name('quehaceres_update');

    Route::delete('/delet', [QuehaceresController::class, 'destroy'])->name('quehaceres_delete');
});
