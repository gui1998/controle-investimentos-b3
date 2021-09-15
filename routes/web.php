<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
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


Auth::routes();

Route::get('/me', 'ProfileController@me')->name('me');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/about', function () {
        return view('about');
    })->name('about');

    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');

// Resource Route for stocktypes.
    Route::resource('stockTypes', 'StockTypeController');
// Route for get stockTypes for yajra post request.
    Route::get('get-stockTypes', 'StockTypeController@getStockTypes')->name('get-stockTypes');
    Route::get('list-stockTypes', 'StockTypeController@getListStockTypes')->name('list-stockTypes');

// Resource Route for sectors.
    Route::resource('sectors', 'SectorController');
// Route for get stocktypes for yajra post request.
    Route::get('get-sectors', 'SectorController@getSectors')->name('get-sectors');
    Route::get('list-sectors', 'SectorController@getListSector')->name('list-sectors');

// Resource Route for stocks.
    Route::resource('stocks', 'StockController');
// Route for get stocktypes for yajra post request.
    Route::get('get-stocks', 'StockController@getStocks')->name('get-stocks');
    Route::get('list-stocks', 'StockController@getListStocks')->name('list-stocks');

// Resource Route for income types.
    Route::resource('incomeTypes', 'IncomeTypeController');
// Route for get incomeTypes for yajra post request.
    Route::get('get-incomeTypes', 'IncomeTypeController@getIncomeTypes')->name('get-incomeTypes');
    Route::get('list-incomeTypes', 'IncomeTypeController@getListIncomeTypes')->name('list-incomeTypes');

// Resource Route for operation type.
    Route::resource('operationTypes', 'OperationTypeController');
// Route for get operationTypes for yajra post request.
    Route::get('get-operationTypes', 'OperationTypeController@getOperationTypes')->name('get-operationTypes');
    Route::get('list-operationTypes', 'OperationTypeController@getListOperationTypes')->name('list-operationTypes');

// Resource Route for brokers.
    Route::resource('brokers', 'BrokerController');
// Route for get brokers for yajra post request.
    Route::get('get-brokers', 'BrokerController@getBrokers')->name('get-brokers');
    Route::get('list-brokers', 'BrokerController@getListBrokers')->name('list-brokers');

// Resource Route for incomes.
    Route::resource('incomes', 'IncomeController');
// Route for get stocktypes for yajra post request.
    Route::get('get-incomes', 'IncomeController@getIncomes')->name('get-incomes');

// Resource Route for operations.
    Route::resource('operations', 'OperationController');
// Route for get stocktypes for yajra post request.
    Route::get('get-operations', 'OperationController@getOperations')->name('get-operations');

    Route::resource('investments', 'InvestmentController');

    Route::resource('results', 'ResultController');
    Route::get('list-results', 'ResultController@getListResults')->name('list-results');
});
