<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

// Route::get('tender', [App\Http\Controllers\TenderController::class, 'index'])->name('tender.index');
// route group tender
Route::prefix('tender')->name('tender.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\TenderController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\TenderController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\TenderController::class, 'store'])->name('store'); 
    Route::get('/edit/{id}', [App\Http\Controllers\TenderController::class, 'edit'])->name('edit');   
    Route::delete('/destroy/{id}', [App\Http\Controllers\TenderController::class, 'destroy'])->name('destroy'); 

    Route::get('/detail/{id}', [App\Http\Controllers\TenderDetailController::class, 'index'])->name('detail');

    // Detail Criteria Data
    Route::post('/criteria_data/store', [App\Http\Controllers\TenderDetailController::class, 'saveCriteriaData'])->name('criteria_data.save');
    Route::delete('/delete/{id}', [App\Http\Controllers\TenderDetailController::class, 'destroy'])->name('delete');
});

Route::prefix('vendor')->name('vendor.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\VendorController::class, 'index'])->name('index');   
    Route::get('/create', [App\Http\Controllers\VendorController::class, 'create'])->name('create'); 
    Route::post('/store', [App\Http\Controllers\VendorController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [App\Http\Controllers\VendorController::class, 'edit'])->name('edit'); 
    Route::post('/update/{id}', [App\Http\Controllers\VendorController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [App\Http\Controllers\VendorController::class, 'destroy'])->name('destroy');
});

Route::prefix('criteria')->name('criteria.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\CriteriaController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CriteriaController::class, 'create'])->name('create'); 
    Route::post('/store', [App\Http\Controllers\CriteriaController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [App\Http\Controllers\CriteriaController::class, 'edit'])->name('edit'); 
    Route::post('/update/{id}', [App\Http\Controllers\CriteriaController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [App\Http\Controllers\CriteriaController::class, 'destroy'])->name('destroy');
});
