<?php

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

Auth::routes();

Route::group(['middleware' => ['isActive']], function () {
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');
    Route::post('/portal', 'HomeController@changePortal');

    Route::get('/changepassword', 'UserController@changePassword');
    Route::post('/updatepassword', 'UserController@updatePassword');

    //USERS
    Route::group(['middleware' => ['hasAccess:7']], function () {
        Route::resource('users', 'UserController')->except(['create', 'destroy']);
        Route::post('/users/resetpassword', 'UserController@resetPassword');
    });

    //SETTINGS
    Route::group(['middleware' => ['hasAccess:10']], function () {
        Route::resource('user-groups', 'UserGroupController')->except(['show', 'destroy']);
        Route::resource('member-types', 'MembershipTypeController')->except(['show', 'destroy']);
        Route::resource('destinations', 'DestinationController')->except(['show']);
        Route::resource('consultants', 'ConsultantController')->except(['show', 'destroy']);
    });

    //AUDIT LOG
    Route::group(['middleware' => ['hasAccess:9']], function () {
        Route::resource('audit-log', 'AuditLogController')->only(['index', 'show']);
    });

    /* MEMBERS */
    Route::group(['middleware' => ['hasAccess:2']], function () {
        Route::resource('members', 'MemberController')->except(['destroy']);
        Route::post('/members/createPayment', 'MemberController@createPayment');
        Route::post('/members/createVoucher', 'MemberController@createVoucher');
    });

    // ACCOUNTS
    Route::group(['middleware' => ['hasAccess:3']], function () {
        Route::resource('accounts', 'AccountController')->only(['index', 'show']);
        Route::post('/accounts/createInvoice', 'AccountController@createInvoice');
        Route::post('/accounts/createPayment', 'AccountController@createPayment');
        Route::post('/accounts/createVoucher', 'AccountController@createVoucher');
        Route::get('/accounts/members/{account}', 'AccountController@editMembers');
        Route::get('/accounts/addMember/{account}', 'AccountController@addMember');
        Route::post('/accounts/addMember/store', 'AccountController@storeMember');
        Route::post('/accounts/removeMember/{account}', 'AccountController@removeMember');
    });

    /* VOUCHERS */
    Route::group(['middleware' => ['hasAccess:4']], function () {
        Route::resource('vouchers', 'VoucherController');
        // Route::post('/vouchers/{id}/destroy', 'VoucherControler@destroy');
    });

    /* PAYMENTS */
    Route::group(['middleware' => ['hasAccess:5']], function () {
        Route::resource('payments', 'PaymentController')->only(['index', 'store']);
    });

    // INVOICE
    Route::group(['middleware' => ['hasAccess:8']], function () {
        Route::resource('invoices', 'InvoiceController')->except(['destroy']);
        Route::post('/invoices/storePayment', 'InvoiceController@storePayment');
    });

    /* REPORTS */
    Route::group(['middleware' => ['hasAccess:1']], function () {
        Route::get('/getaccounts', 'ReportController@getAccounts')->name('getaccounts');
        Route::get('/getvouchers', 'ReportController@getVouchers')->name('getvouchers');
        Route::get('/allmembers', 'ReportController@allMembers')->name('allmembers');
        Route::get('/dtAccounts', 'ReportController@dtAccounts')->name('dtAccounts');
        Route::get('/dtVouchers', 'ReportController@dtVouchers')->name('dtVouchers');
        Route::get('reports/collection', 'ReportController@collection');
        Route::get('reports/members', 'ReportController@members');
        Route::get('reports/accounts', 'ReportController@accounts');
        Route::get('reports/vouchers', 'ReportController@vouchers');
        Route::get('reports/redemption', 'ReportController@redemption');
        Route::get('reports/redemption/details/{year}/{month}/{destination}/{status}', 'ReportController@redemptionDetails');
        Route::get('reports/validity', 'ReportController@validity');
        Route::get('reports/excel/collection/{from}/to/{to}', 'ReportController@exportCollectionExcel');
        Route::get('reports/excel/members/{status}', 'ReportController@exportMembersExcel');
        Route::get('reports/excel/accounts/{destination}', 'ReportController@exportAccountsExcel');
        Route::get('reports/excel/vouchers/{status}/from/{from}/to/{to}/account/{account}/des/{destination}', 'ReportController@exportVouchersExcel');
        Route::get('reports/excel/redemption/{type}/year/{year}/from/{from}/to/{to}/des/{des}', 'ReportController@exportRedemptionExcel');
        Route::get('reports/excel/redemption/part/{type}/id/{id}/from/{from}/to/{to}/des/{des}', 'ReportController@exportRedemptionPartExcel');
        Route::get('reports/excel/validity/{id}/from/{from}/to/{to}', 'ReportController@exportValidityExcel');
        Route::get('reports/pdf/collection/{data}', 'ReportController@exportCollectionPdf');
        Route::get('reports/pdf/accounts/{destination}', 'ReportController@exportAccountsPdf');
        Route::get('reports/pdf/members/{status}', 'ReportController@exportMembersPdf');
        Route::get('reports/pdf/vouchers/{status}/from/{from}/to/{to}/account/{account}/des/{destination}', 'ReportController@exportVouchersPdf');
        Route::get('reports/pdf/redemption/{type}/year/{year}/from/{from}/to/{to}/des/{des}', 'ReportController@exportRedemptionPdf');
        Route::get('reports/pdf/redemption/part/{type}/id/{id}/from/{from}/to/{to}/des/{des}', 'ReportController@exportRedemptionPartPdf');
        Route::get('reports/pdf/validity/{id}/from/{from}/to/{to}', 'ReportController@exportValidityPdf');

        //DASHBOARD
        Route::get('/dashboard', 'DashboardController@index');
        Route::get('/dashboard/getCollections/{year}', 'DashboardController@getCollections');
        Route::get('/dashboard/getVouchers/{year}', 'DashboardController@getVouchers');
        Route::get('/dashboard/accountStatus', 'DashboardController@getAccountStatus');
        Route::get('/dashboard/voucherStatus', 'DashboardController@getVoucherStatus');
    });

    /* REDEMPTION */
    Route::group(['middleware' => ['hasAccess:6']], function () {
        Route::get('/redemptions', 'RedemptionController@index');
        Route::post('/redemptions/updatestatus', 'RedemptionController@updateStatus');
        Route::post('/redemptions/redeemed', 'RedemptionController@redeemed');
    });
});
