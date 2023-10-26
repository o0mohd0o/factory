<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\HomeController;
//use App\Http\Controllers\ItemsCardController;
use App\Models\DepartmentItem;
use Illuminate\Support\Carbon;

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

Route::group(['middleware' => ['lang']], function () {
    Auth::routes();
});

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

Route::get('/', function () {
    return view('home-page');
});

Route::get('/dashboard', function () {
    if (auth()->user()->can('manage_users')) {
        return view('dashboard');
    }
    return view('home-page');
})->name('dashboard')->middleware('auth');



Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('main');
//Route::resource('items', 'App\Http\Controllers\ItemsCardController');
//Route::resource('depart', 'App\Http\Controllers\DepartmentController');
Route::get('fetch-items', 'App\Http\Controllers\PrintQrcodeController@fetchitems');
Route::get('last-serial/{id}', 'App\Http\Controllers\PrintQrcodeController@getLastSerial');
Route::get('print-data', 'App\Http\Controllers\PrintQrcodeController@printData');
Route::post('print-data', 'App\Http\Controllers\PrintQrcodeController@printData');
Route::get('print-data-all', 'App\Http\Controllers\PrintQrcodeController@printDataAll');
Route::post('print-data-all', 'App\Http\Controllers\PrintQrcodeController@printDataAll');
Route::resource('qrcode', 'App\Http\Controllers\PrintQrcodeController');
//Route::resource('transfer', 'App\Http\Controllers\TransferController');

Route::get('print-transfer', [App\Http\Controllers\Ajax\AjaxTransferController::class, 'print']);
Route::post('print-transfer', 'App\Http\Controllers\Ajax\AjaxTransferController@print');
//Route::get('fetch-departmets', 'App\Http\Controllers\TransferController@fetchDepartments');
Auth::routes();

//Get item cards from hesabat app
//Route::get('items/hesabat/card-item', [ItemsCardController::class, 'cardItem'])->name('items.cardItem');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/empty-data', function () {
    \DB::statement("TRUNCATE `department_daily_reports`");
    \DB::statement("TRUNCATE `department_items`");
    \DB::statement("TRUNCATE `gold_transforms`");
    \DB::statement("TRUNCATE `gold_transform_used_items`");
    \DB::statement("TRUNCATE `gold_losses`");
    \DB::statement("TRUNCATE `gold_transform_new_items`");
    \DB::statement("TRUNCATE `opening_balances`");
    \DB::statement("TRUNCATE `opening_balance_details`");
    \DB::statement("TRUNCATE `opening_balance_reports`");
    \DB::statement("TRUNCATE `transfers`");
    \DB::statement("TRUNCATE `transfer_reports`");
})->name('emptyData');
