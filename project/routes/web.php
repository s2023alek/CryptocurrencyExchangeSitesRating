<?php

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


Route::get('/', [\App\Http\Controllers\Home::class, 'show'])->name('show');


Route::group(['prefix' => 'coin'], function () {
    Route::get('/{coinUrlName}', [\App\Http\Controllers\Coin::class, 'show'])->name('show');
});
Route::get('/coins', [\App\Http\Controllers\Coins::class, 'show'])->name('show');
Route::get('/exchanges', [\App\Http\Controllers\Exchanges::class, 'show'])->name('show');

Route::group(['prefix' => 'exchange'], function () {
    Route::get('/{exchangeUrlName}', [\App\Http\Controllers\Exchange::class, 'show'])->name('show');
});

Route::get('/admin', [\App\Http\Controllers\Admin::class, 'index'])->name('index');
Route::get('/admin2', [\App\Http\Controllers\Admin::class, 'index2'])->name('index2');
Route::get('/admin-meta', [\App\Http\Controllers\Admin::class, 'indexMeta'])->name('indexMeta');

Route::group(['prefix' => 'api'], function () {
    Route::post('/meta-data-item', [\App\Http\Controllers\APIMetaData::class, 'saveSingle'])->name('saveSingle');
    Route::post('/meta-data', [\App\Http\Controllers\APIMetaData::class, 'saveList'])->name('saveList');
    Route::post('/refs', [\App\Http\Controllers\APIRefs::class, 'save'])->name('save');
    Route::post('/initialData', [\App\Http\Controllers\Admin::class, 'uploadInitialData'])->name('uploadInitialData');
    Route::post('/siteSettings', [\App\Http\Controllers\Admin::class, 'saveSiteSettings'])->name('saveSiteSettings');
    Route::post('/uploadSiteMap', [\App\Http\Controllers\Admin::class, 'uploadSiteMap'])->name('uploadSiteMap');
    Route::delete('/dataCache', [\App\Http\Controllers\Admin::class, 'clearDataCache'])->name('clearDataCache');
});


