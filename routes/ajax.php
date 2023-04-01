<?php

use App\Http\Controllers\Ajax\AjaxDepartmentController;
use App\Http\Controllers\Ajax\AjaxGeneralSettingsController;
use App\Http\Controllers\Ajax\AjaxItemCardController;
use App\Http\Controllers\Ajax\AjaxItemCardSettingsController;
use App\Http\Controllers\Ajax\AjaxOpeningBalanceController;
use App\Http\Controllers\Ajax\AjaxQrcodeController;
use App\Http\Controllers\Ajax\AjaxReportController;
use App\Http\Controllers\Ajax\AjaxTransferController;
use Illuminate\Support\Facades\Route;

//Important Note: All this routes prefixed with /ajax


//Departments CRUD
Route::get('/departments', [AjaxDepartmentController::class, 'index'])->name('ajax.departments.index');
Route::post('/departments/store', [AjaxDepartmentController::class, 'store'])->name('ajax.departments.store');
Route::get('/departments/{department}/edit', [AjaxDepartmentController::class, 'edit'])->name('ajax.departments.edit');
Route::post('/departments/update', [AjaxDepartmentController::class, 'update'])->name('ajax.departments.update');
Route::post('/departments/{department}/delete', [AjaxDepartmentController::class, 'delete'])->name('ajax.departments.delete');

//Transfers Routes
//Fetch department items
Route::get('/transfers/fetch-department-items', [AjaxTransferController::class, 'fetchDepartmentItems'])->name('transfers.fetchDepartmentItems');
//Fetch departments
Route::get('/transfers/fetch-departments', [AjaxTransferController::class, 'fetchDepartments'])->name('transfers.fetchDepartments');
//Store transfer
Route::post('/departments/{department}/transfers/store', [AjaxTransferController::class, 'store'])->name('ajax.transfers.store');
//Get all department transfers
Route::get('/departments/{department}/transfers/index', [AjaxTransferController::class, 'index'])->name('ajax.transfers.index');
//Get all department transfers using navigator
Route::get('/departments/{department}/transfers-navigator/index', [AjaxTransferController::class, 'navigator'])->name('ajax.transfers.navigator');

//Get opening balances for the department on a specified day
Route::get('/departments/{department}/opening-balances/index', [AjaxOpeningBalanceController::class, 'index'])->name('ajax.openingBalances.index');
//create opening balances for the department
Route::get('/departments/{department}/opening-balances/create', [AjaxOpeningBalanceController::class, 'create'])->name('ajax.openingBalances.create');
//Store the openeing balance for the department
Route::post('/departments/{department}/opening-balances/store', [AjaxOpeningBalanceController::class, 'store'])->name('ajax.openingBalances.store');
//get the openeing balance for the department
Route::get('/opening-balances/{openingBalance}/edit', [AjaxOpeningBalanceController::class, 'edit'])->name('ajax.openingBalances.edit');
//update the openeing balance for the department
Route::post('/opening-balances/{openingBalance}/update', [AjaxOpeningBalanceController::class, 'update'])->name('ajax.openingBalances.update');
//delete the openeing balance for the department
Route::post('/opening-balances/{openingBalance}/delete', [AjaxOpeningBalanceController::class, 'delete'])->name('ajax.openingBalances.delete');

//QrCode
//Get qrcode
Route::get('/qr-code/index', [AjaxQrcodeController::class, 'index'])->name('ajax.qrcodes.index');
//create qrcode
Route::get('/qr-code/create', [AjaxQrcodeController::class, 'create'])->name('ajax.qrcodes.create');
//Store qrcode
Route::post('/qr-code/store', [AjaxQrcodeController::class, 'store'])->name('ajax.qrcodes.store');
//get qrcode
Route::get('/qr-code/{qrcode}/edit', [AjaxQrcodeController::class, 'edit'])->name('ajax.qrcodes.edit');
//update qrcode
Route::post('/qr-code/{qrcode}/update', [AjaxQrcodeController::class, 'update'])->name('ajax.qrcodes.update');
//delete qrcode
Route::post('/qr-code/{qrcode}/delete', [AjaxQrcodeController::class, 'delete'])->name('ajax.qrcodes.delete');
//End of Qrcode


//item cards
//Get item cards
Route::get('/item-card/index', [AjaxItemCardController::class, 'index'])->name('ajax.itemCards.index');
//Get item cards per parent item
Route::get('/item-card/parent-item-index', [AjaxItemCardController::class, 'getItemsPerParent'])->name('ajax.itemCards.getItemsPerParent');
//Get item cards per parent item
Route::get('/item-card/refresh-current-level-items', [AjaxItemCardController::class, 'refreshCurrentLevelItems'])->name('ajax.itemCards.refreshCurrentLevelItems');
//create item cards
Route::get('/item-card/create', [AjaxItemCardController::class, 'create'])->name('ajax.itemCards.create');
//Store item cards
Route::post('/item-card/store', [AjaxItemCardController::class, 'store'])->name('ajax.itemCards.store');
//get item cards
Route::get('/item-card/{itemCard}/edit', [AjaxItemCardController::class, 'edit'])->name('ajax.itemCards.edit');
//update item cards
Route::post('/item-card/{itemCard}/update', [AjaxItemCardController::class, 'update'])->name('ajax.itemCards.update');
//delete item cards
Route::post('/item-card/{itemCard}/delete', [AjaxItemCardController::class, 'delete'])->name('ajax.itemCards.delete');
//Fetch item cards which doesn't have childs
Route::get('/item-cards/fetch-items', [AjaxItemCardController::class, 'fetchItemCards'])->name('ajax.itemCards.fetchItemCards');
//Fetch all item cards
Route::get('/item-cards/fetch-all-items', [AjaxItemCardController::class, 'fetchAllItemCards'])->name('ajax.itemCards.fetchAllItemCards');

//End of item cards


//Reports
Route::post('departments/transfer-reports/show', [AjaxReportController::class, 'transferReports'])->name('departments.transferReports.show');
//department Daily Reports
Route::post('departments/daily-reports/show', [AjaxReportController::class, 'dailyReports'])->name('departments.dailyReports.show');
//department Daily Reports in total
Route::post('departments/daily-reports-in-total/show', [AjaxReportController::class, 'dailyReportsInTotal'])->name('departments.dailyReportsInTotal.show');
//department karat difference Reports in total
Route::post('departments/karat-difference-reports/show', [AjaxReportController::class, 'karatDifferenceReports'])->name('departments.karatDifferenceReports.show');


//General settings 
Route::post('general-settings', [AjaxGeneralSettingsController::class, 'index'])->name('ajax.gereralSettings.index');
//item card settings 
Route::get('/item-card-settings', [AjaxItemCardSettingsController::class, 'show'])->name('ajax.itemCardSettings.show');
Route::post('/item-card-settings', [AjaxItemCardSettingsController::class, 'update'])->name('ajax.itemCardSettings.update');