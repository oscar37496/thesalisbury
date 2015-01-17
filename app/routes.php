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
	Route::get('dashboard', 'AccountController@getSysadminDashboard');
	
	Route::get('users', 'AccountController@getAdminUsers');
	Route::get('users/{id}/{action}', 'AccountController@userAction');
	
	Route::get('notifications/{id}/{action}', 'AccountController@notificationAction');
	
	Route::get('transactions', 'AccountController@getAdminTransactions');
	
	Route::get('cards', 'AccountController@getAdminCards');
	Route::get('cards/{id}/{action}', 'AccountController@cardAction');
	
	Route::get('stocktake', 'AccountController@getAdminStocktake');
	Route::get('stocktake/{id}/{volume}', 'AccountController@setStocktake');

});

Route::group(array('prefix' => 'account/sysadmin', 'before' => 'auth.sysadmin'), function() {
	Route::get('/', function(){
		return Redirect::to('/account/sysadmin/dashboard');
	});
	Route::get('dashboard', 'AccountController@getSysadminDashboard');
	Route::get('transactions', 'AccountController@getSysadminTransactions');
	Route::get('transactions/{transactionid}/{userid}', 'AccountController@linkBankTransaction')->where('userid', '[0-9]+');
	Route::get('transactions/{transactionid}/{type}', 'AccountController@setBankTransactionType')->where('type', '[A-Za-z]+');
	Route::get('transactions/{transactionid}/{type}/{user}', 'AccountController@setBankTransactionPayoutType')->where('type', '[A-Za-z]+');
	Route::post('transactions/upload', 'AccountController@uploadBankTransactions');
	
	Route::get('cashtransactions', 'AccountController@getSysadminCashTransactions');
	Route::get('cashtransactions/{transactionid}/{type}', 'AccountController@setCashTransactionType')->where('type', '[A-Za-z]+');
	
	Route::get('purchases', 'AccountController@getSysadminPurchases');
	Route::get('purchases/bank/{id}/{transactionid}', 'AccountController@linkPurchaseBank');
	Route::get('purchases/cash/{id}', 'AccountController@linkPurchaseCash');
	
	Route::get('operations', 'AccountController@getSysadminOperations');
	Route::get('operations/cash/{amount}', 'AccountController@updateCash');
	Route::get('operations/credit/{user}/{amount}', 'AccountController@updateCredit');
	Route::get('operations/purchase/{id}/{volume}/{cost}', 'AccountController@updatePurchase');
	Route::get('operations/payout/{user}/{amount}', 'AccountController@setPayout');
});

Route::group(array('prefix' => 'account/charles', 'before' => 'auth.charles'), function() {
	Route::get('users', 'AccountController@getCharlesUsers');
	Route::get('users/{id}', 'AccountController@updateLastMessaged');
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

