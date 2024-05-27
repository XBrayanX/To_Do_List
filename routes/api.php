<?php
use App\Http\Controllers\todoListController;
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

Route::prefix( 'todolist' )->group( function () {
    Route::get( '/index', [todoListController::class, 'index'] )->name( 'todolist_index' );

    Route::get( '/show', [todoListController::class, 'show'] )->name( 'todolist_show' );

    Route::post( '/store', [todoListController::class, 'store'] )->name( 'todolist_store' );

    Route::put( '/update', [todoListController::class, 'update'] )->name( 'todolist_update' );

    Route::delete( '/delete', [todoListController::class, 'destroy'] )->name( 'todolist_delete' );
} );
