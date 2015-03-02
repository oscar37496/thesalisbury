<?php

/*
 |--------------------------------------------------------------------------
 | Application Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register all of the routes for an application.
 | It's a breeze. Simply tell Laravel the URIs it should respond to
 | and give it the Closure to execute when that URI is requested.
 |
 */
 
Route::get('/', 'HomeController@getLanding');

Route::group(array('prefix' => 'account', 'before' => 'auth'), function() {
	Route::get('/', function(){
		return Redirect::to('/account/dashboard');
	});
	Route::get('dashboard', 'AccountController@getDashboard');
	//Route::get('statistics', 'AccountController@getStatistics');
	Route::get('transactions', 'AccountController@getTransactions');
	Route::get('cards', 'AccountController@getCards');
	Route::get('friends', 'AccountController@getFriends');
});

Route::group(array('prefix' => 'account/admin', 'before' => 'auth.admin'), function() {
	Route::get('/', function(){
		return Redirect::to('/account/admin/dashboard');
	});
	//Route::get('dashboard', 'AccountController@getSysadminDashboard');
	
	Route::get('users', 'AdminController@getAdminUsers');
	Route::get('users/{id}/{action}', 'AdminController@userAction');
	
	Route::get('notifications/{id}/{action}', 'AdminController@notificationAction');
	
	Route::get('transactions', 'AdminController@getAdminTransactions');
	
	Route::get('cards', 'AdminController@getAdminCards');
	Route::get('cards/{id}/{action}', 'AdminController@cardAction');
	
	Route::get('stocktake', 'AdminController@getAdminStocktake');
	Route::get('stocktake/{id}/{volume}', 'AdminController@setStocktake');

});

Route::group(array('prefix' => 'account/sysadmin', 'before' => 'auth.sysadmin'), function() {
	Route::get('/', function(){
		return Redirect::to('/account/sysadmin/dashboard');
	});
	Route::get('dashboard', 'SysadminController@getSysadminDashboard');
	Route::get('transactions', 'SysadminController@getSysadminTransactions');
	Route::get('transactions/{transactionid}/{userid}', 'SysadminController@linkBankTransaction')->where('userid', '[0-9]+');
	Route::get('transactions/{transactionid}/{type}', 'SysadminController@setBankTransactionType')->where('type', '[A-Za-z]+');
	Route::get('transactions/{transactionid}/{type}/{user}', 'SysadminController@setBankTransactionPayoutType')->where('type', '[A-Za-z]+');
	Route::post('transactions/upload', 'SysadminController@uploadBankTransactions');
	
	Route::get('cashtransactions', 'SysadminController@getSysadminCashTransactions');
	Route::get('cashtransactions/{transactionid}/{type}', 'SysadminController@setCashTransactionType')->where('type', '[A-Za-z]+');
	
	Route::get('purchases', 'SysadminController@getSysadminPurchases');
	Route::get('purchases/bank/{id}/{transactionid}', 'SysadminController@linkPurchaseBank');
	Route::get('purchases/cash/{id}', 'SysadminController@linkPurchaseCash');
	
	Route::get('operations', 'SysadminController@getSysadminOperations');
	Route::get('operations/csv', 'SysAdminController@getCSV');
	Route::get('operations/cash/{amount}', 'SysadminController@updateCash');
	Route::get('operations/credit/{user}/{amount}', 'SysadminController@updateCredit');
	Route::get('operations/purchase/{id}/{volume}/{cost}', 'SysadminController@updatePurchase');
	Route::get('operations/payout/{user}/{amount}', 'SysadminController@setPayout');
});

Route::group(array('prefix' => 'account/charles', 'before' => 'auth.charles'), function() {
	Route::get('users', 'CharlesController@getCharlesUsers');
	Route::get('users/{id}', 'CharlesController@updateLastMessaged');
});

Route::get('auth/fb', 'AuthController@authFacebook');
Route::get('auth/fb/callback', 'AuthController@authFacebookCallback');
Route::get('auth/fb/deauth', 'AuthController@deauthFacebook');

Route::group(array('prefix' => 'api', 'before' => 'force.ssl|api'), function() {
	Route::resource('sku', 'SkuController');
	Route::resource('tag', 'TagController');
	Route::resource('user', 'UserController');
	Route::resource('transaction', 'TransactionController');
});

App::missing(function($exception)
{
    return Redirect::to('/account/dashboard');
});

