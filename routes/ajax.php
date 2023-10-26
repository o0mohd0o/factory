<?php

use App\Http\Controllers\Ajax\AjaxDepartmentController;
use App\Http\Controllers\Ajax\AjaxGeneralSettingsController;
use App\Http\Controllers\Ajax\AjaxGoldTransformController;
use App\Http\Controllers\Ajax\AjaxItemCardController;
use App\Http\Controllers\Ajax\AjaxItemCardSettingsController;
use App\Http\Controllers\Ajax\AjaxOpeningBalanceController;
use App\Http\Controllers\Ajax\AjaxQrcodeController;
use App\Http\Controllers\Ajax\AjaxReportController;
use App\Http\Controllers\Ajax\AjaxTransferController;
use App\Http\Controllers\Ajax\AjaxManageUsersController;
use App\Http\Controllers\Ajax\AjaxOfficeTransferController;
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
Route::get('/transfers/fetch-department-items', [AjaxItemCardController::class, 'fetchDepartmentItems'])->name('items.fetchDepartmentItems');
//Fetch departments
Route::get('/transfers/fetch-departments', [AjaxTransferController::class, 'fetchDepartments'])->name('transfers.fetchDepartments');
//Store transfer
Route::post('/departments/{department}/transfers/store', [AjaxTransferController::class, 'store'])->name('ajax.transfers.store');
//Get all department transfers
Route::get('/departments/{department}/transfers/index', [AjaxTransferController::class, 'index'])->name('ajax.transfers.index');
//Get all department transfers using navigator
Route::get('/departments/{department}/transfers-navigator/index', [AjaxTransferController::class, 'navigator'])->name('ajax.transfers.navigator');

Route::prefix('office-transfers')->group(function () {
    Route::get('/', [AjaxOfficeTransferController::class, 'index'])->name('ajax.officeTransfers.index');
    //create opening balances for the department
    Route::get('/create', [AjaxOfficeTransferController::class, 'create'])->name('ajax.officeTransfers.create');
    //Store the office transfer for the department
    Route::post('/store', [AjaxOfficeTransferController::class, 'store'])->name('ajax.officeTransfers.store');
    //get the office transfer for the department
    Route::get('/{officeTransfer}/edit', [AjaxOfficeTransferController::class, 'edit'])->name('ajax.officeTransfers.edit');
    //update the office transfer for the department
    Route::post('/{officeTransfer}/update', [AjaxOfficeTransferController::class, 'update'])->name('ajax.officeTransfers.update');
    //delete the office transfer for the department
    Route::post('/{officeTransfer}/delete', [AjaxOfficeTransferController::class, 'delete'])->name('ajax.officeTransfers.delete');
});
Route::prefix('gold-transform')->group(function () {
    Route::get('/', [AjaxGoldTransformController::class, 'index'])->name('ajax.goldTransforms.index');
    //create opening balances for the department
    Route::get('/create', [AjaxGoldTransformController::class, 'create'])->name('ajax.goldTransforms.create');
    //Store the office transfer for the department
    Route::post('/store', [AjaxGoldTransformController::class, 'store'])->name('ajax.goldTransforms.store');
    //get the office transfer for the department
    Route::get('/{goldTransform}/edit', [AjaxGoldTransformController::class, 'edit'])->name('ajax.goldTransforms.edit');
    //update the office transfer for the department
    Route::post('/{goldTransform}/update', [AjaxGoldTransformController::class, 'update'])->name('ajax.goldTransforms.update');
    //delete the office transfer for the department
    Route::post('/{goldTransform}/delete', [AjaxGoldTransformController::class, 'delete'])->name('ajax.goldTransforms.delete');
});

//Get opening balances for the department on a specified day
Route::get('/departments/opening-balances/index', [AjaxOpeningBalanceController::class, 'index'])->name('ajax.openingBalances.index');
//create opening balances for the department
Route::get('/departments/opening-balances/create', [AjaxOpeningBalanceController::class, 'create'])->name('ajax.openingBalances.create');
//Store the openeing balance for the department
Route::post('/departments/opening-balances/store', [AjaxOpeningBalanceController::class, 'store'])->name('ajax.openingBalances.store');
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
Route::post('departments/statement/show', [AjaxReportController::class, 'departmentStatement'])->name('reports.departmentStatement.show');
//department Daily Reports in total
Route::post('departments/daily-reports-in-total/show', [AjaxReportController::class, 'dailyReportsInTotal'])->name('departments.dailyReportsInTotal.show');
//department Daily Reports
Route::post('departments/daily-reports/show', [AjaxReportController::class, 'dailyReports'])->name('departments.dailyReports.show');
//department purity difference Reports in total
Route::post('departments/purity-difference-reports/show', [AjaxReportController::class, 'purityDifference'])->name('reports.purityDifference.show');
//department Gold Losses Reports in total
Route::post('departments/gold-losses/show', [AjaxReportController::class, 'goldLosses'])->name('departments.goldLosses.show');


//General settings
Route::post('general-settings', [AjaxGeneralSettingsController::class, 'index'])->name('ajax.gereralSettings.index');
//item card settings
Route::get('/item-card-settings', [AjaxItemCardSettingsController::class, 'show'])->name('ajax.itemCardSettings.show');
Route::post('/item-card-settings', [AjaxItemCardSettingsController::class, 'update'])->name('ajax.itemCardSettings.update');


// manage users

Route::get('/manageusers', [AjaxManageUsersController::class, 'index'])->name('ajax.manageUsers.index');
Route::get('/getusers', [AjaxManageUsersController::class, 'getUsersData'])->name('ajax.manageUsers.users');
Route::post('/updateRoles', [AjaxManageUsersController::class, 'update'])->name('ajax.manageUsers.update');
Route::get('/user-roles', [AjaxManageUsersController::class, 'userRole'])->name('ajax.manageUsers.userRole');
Route::get('/add-new-user', [AjaxManageUsersController::class, 'newUser'])->name('ajax.newUser.index');
Route::post('/add-new-user', [AjaxManageUsersController::class, 'addNewUser'])->name('ajax.newUser.add');
