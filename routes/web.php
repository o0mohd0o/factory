<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\HomeController;
//use App\Http\Controllers\ItemsCardController;
use App\Models\DepartmentDailyReport;
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
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
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


//I make this functin due to tareklance server error on task scheduling
Route::get('cron-jobs/departments/generate-daily-report', function () {
    $departmentsItems = DepartmentItem::with(['department'])
        ->where('current_weight', '>', 0)
        ->get();

    foreach ($departmentsItems as $item) {
        DepartmentDailyReport::create([
            'previous_balance' => $item->current_weight,
            'current_balance' => $item->current_weight,
            'kind' => $item->kind,
            'date' => Carbon::today()->format('Y-m-d'),
            'kind_name' => $item->kind_name,
            'karat' => $item->karat,
            'department_id' => $item->department_id,
            'department_name' => $item->department->name,
        ]);
    }
    \Log::channel('departmentsDailyReports')->info("Successfully excuted departments daily reports.");
})->name('cronJobs.departmentsDailyReports');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
