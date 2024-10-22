<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MallController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\StoreController;

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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('test', 'App\Http\Controllers\ShopController@test')->name('zkteco');

Route::group(["middleware" => ["shopify-auth"]], function () {

    // ShopController
    //install and unintstall
    Route::get('install', 'App\Http\Controllers\ShopController@generate_install_url')->name('install');
    Route::any('generate_token', 'App\Http\Controllers\ShopController@generate_and_save_token')->name('generate_token');
    Route::any('uninstall', 'App\Http\Controllers\ShopController@uninstall')->name('uninstall');
    //gdpr
    Route::any('gdpr_view_customer', 'App\Http\Controllers\ShopController@gdpr_view_customer');
    Route::any('gdpr_delete_customer', 'App\Http\Controllers\ShopController@gdpr_delete_customer');
    Route::any('gdpr_delete_shop', 'App\Http\Controllers\ShopController@gdpr_delete_shop');
    //app view and save setting
    Route::get('send_support_email', 'App\Http\Controllers\ShopController@sendSupportEmail')->name('sendSupportEmail');
    Route::get('app_view', 'App\Http\Controllers\ShopController@app_view')->name('app_view');
    Route::get('custom_sales', 'App\Http\Controllers\ShopController@customSales')->name('customSales');
    Route::get('/', 'App\Http\Controllers\ShopController@app_view')->name('app_view');
    Route::post('save_setting', 'App\Http\Controllers\ShopController@saveSetting')->name('saveSetting');
    Route::get('get_settings', 'App\Http\Controllers\ShopController@getSettings')->name('getSettings');

    // ShopController

    // BillingController
    Route::get('create_charge/{id}', 'App\Http\Controllers\BillingController@create_charge')->name('charge.create');
    Route::get('change_store_plan/{id}', 'App\Http\Controllers\BillingController@change_plan')->name('plan.change');
    Route::post('create_charge', 'App\Http\Controllers\BillingController@createCharge')->name('createCharge');
    Route::post('cancel_charge', 'App\Http\Controllers\BillingController@cancelCharge')->name('cancelCharge');
    Route::post('check_billing', 'App\Http\Controllers\BillingController@check_billing')->name('checkCurrentChargeStatus');
    Route::post('get_current_charge_status', 'App\Http\Controllers\BillingController@checkCurrentChargeStatus')->name('checkCurrentChargeStatus');
    // BillingController

    // AppController
    // Route::get('create','App\Http\Controllers\AppController@create')->name('create');
    Route::post('install_theme_with_embedded', 'App\Http\Controllers\AppController@installThemeWithEmbedded')->name('installThemeWithEmbedded');
    Route::post('install_theme', 'App\Http\Controllers\AppController@installTheme')->name('installTheme');
    Route::post('uninstall_theme', 'App\Http\Controllers\AppController@uninstallTheme')->name('uninstallTheme');
    Route::post('current_country', 'App\Http\Controllers\AppController@currentCountry')->name('currentCountry');
    Route::post('create_report', 'App\Http\Controllers\AppController@createReport')->name('createReport');
    // AppController
    Route::get('create', 'App\Http\Controllers\ShopController@app_view');
    Route::get('step_2', 'App\Http\Controllers\ShopController@app_view');
    Route::get('step_3', 'App\Http\Controllers\ShopController@app_view');
    Route::get('edit/{id}', 'App\Http\Controllers\ShopController@app_view');
    
    Route::get('generate-pos-file', [OrderReportController::class, 'GenerateDailyReport']);
    Route::get('generate-pos-file-hourly', [OrderReportController::class, 'GenerateHourlyReport']);
    Route::get('generate-pos-file-hourly-extended', [OrderReportController::class, 'GenerateHourly18Report']);
});
Route::post('generate-report-decide', [OrderReportController::class, 'generateReportDecide']);
Route::get('/download/{filenames}', 'App\Http\Controllers\AppController@downloadFiles');


Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth']
], function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.index');
    Route::resource('malls', MallController::class);
    Route::resource('users', UserController::class);
    Route::resource('templates', TemplateController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('reports', ReportController::class);
    // Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    // Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('admin.reports.destroy');
    Route::get('/orders_report', [OrderReportController::class, 'index'])->name('orders.index');
    Route::get('/locations/{shop}/{id}', [StoreController::class, 'locationShow'])->name('locations.show');
    Route::post('/get_reports', [OrderReportController::class, 'getReports'])->name('orders.reports');
    Route::post('/location_save', [StoreController::class, 'locationStore'])->name('locations.store');
});
Route::get('get_locations', [OrderReportController::class, 'getLocations'])->name('getLocations');
Route::get('get_locations_for_plan', [OrderReportController::class, 'getLocationsForPlan'])->name('getLocationsForPlan');
Route::get('get_subscriptions_for_plan', [OrderReportController::class, 'getSubscriptionsForPlan'])->name('getSubscriptionsForPlan');

Route::post('/update_subscription_location', [OrderReportController::class, 'updateSubscription'])->name('locations.updateSubscription');




Route::get('get_reports', [OrderReportController::class, 'getAllReports'])->name('getAllReports');
Route::get('edit-report/{id}', [OrderReportController::class, 'editReport'])->name('getEditReport');
Route::post('edit-report', [OrderReportController::class, 'postEditReport'])->name('postEditReport');
Route::post('check-ftp-connection', [OrderReportController::class, 'checkFTPConnection'])->name('checkFTPConnection');
Route::get('get_variables', 'App\Http\Controllers\MallController@getVariable')->name('getVariable');
Route::get('get_malls', 'App\Http\Controllers\MallController@getMalls')->name('getMalls');
Route::get('get_mall/{id}', 'App\Http\Controllers\MallController@getMall')->name('getMalls');
Route::get('get_template_by_mall', 'App\Http\Controllers\MallController@getTemplateByMallId')->name('getTemplateByMallId');
Route::get('get_location_by_mall', 'App\Http\Controllers\StoreController@getLocationByMall')->name('getLocationByMall');
Route::get('get_location_check', 'App\Http\Controllers\StoreController@getLocationCheck')->name('getLocationCheck');
Route::get('get_countries', 'App\Http\Controllers\MallController@getCountry')->name('getCountry');
// Route::get('get_template','App\Http\Controllers\TemplateController@getTemplate')->name('getTemplate');
Route::get('delete_report', 'App\Http\Controllers\OrderReportController@deleteReport')->name('deleteReport');
Route::post('cancel_charge', 'App\Http\Controllers\BillingController@cancelCharge')->name('cancelCharge');
Route::get('get_plans', function () {
    $plans = \App\Models\Plans::all();
    $store_plan = \App\Models\Store::where('shop_url', $_GET['shop'])->first();
    $plan_id = $store_plan->plan_id ?? 1;
    $locations = $store_plan->location_allowed ?? 1;
    return response()->json(['plans' => $plans, 'plan_id' => $plan_id, 'locations' => $locations]);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
