<?php

use App\Http\Controllers\Api\DepartmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ajax\AjaxOpeningBalanceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('openeing-balances/store', [AjaxOpeningBalanceController::class, 'store'])->name('api.openingBalances.store');
Route::get('departments/get-main-department', [DepartmentController::class, 'getMainDepartment'])->name('api.departments.getMainDepartment');
