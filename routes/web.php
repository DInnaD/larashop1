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

// Route::get('/', function () {
//     $date[
//     	'title' => 'Confirm	your email'
//     ];

//     Mail::send('emails.test', $date, function($message){
//     	$message->to('innassik@yahoo.com', 'Hello')->subject('Hello, click link');
//     });
// });

Route::get('/home', 'HomeController@index')->name('home.index');
Route::get('/restore/{user}/restoreUser', 'HomeController@restore');//no table 'trashed_user'!!!!!!!!!!!!!!!!!!!???????????
Route::post('/restore/{user}/restoreUser', 'HomeController@restore');
Route::get('/restore/{trashed_user}/restoreUser', 'HomeController@restore');
Route::get('books/{id}', 'Admin\BooksController@show')->name('books.show');   
Route::get('magazines/{id}', 'Admin\MagazinsController@show')->name('magazines.show');
Route::group(['middleware' => 'guest'], function(){
	//Route::prefix('/{}')->group(function (){});
	//Route::recource('', 'Controller');
	Auth::routes();//->except(['logout']);

	Route::get('/verify/{token}', 'RegisterController@verify');
	//Route::get('/verification', ' VerificationController@resend')->name('verification.resend');	
});

Route::group(['middleware' => 'auth'], function(){
	//Auth::routes()->only(['logout']);	
	Route::prefix('/profile')->group(function(){
			Route::get('/toggleUnVisibleDiscontIdAll/{id}', 'UserController@toggleUnVisibleDiscontIdAll');
			Route::get('/toggleVisibleDiscontGlobal/{id}', 'UserController@toggleVisibleDiscontGlobal');
			Route::get('/toggleVisibleDiscontGlobalAll/{id}', 'UserController@toggleVisibleDiscontGlobalAll');
			Route::get('/toggleSubPrice/{id}', 'UserController@toggleSubPrice');
			Route::get('/toggleAdmin/{id}', 'UserController@toggleAdmin');
			Route::get('/toggleBan/{id}', 'UserController@toggleBan');		
	});
	Route::resource('profile', 'UserController')->only(['index', 'store']);
	Route::prefix('/orders/{order}')->group(function (){
		Route::prefix('purchases')->group(function (){
			Route::get('/toggleIsPausedSubPrice/{id}', 'PurchaseController@toggleIsPausedSubPrice');
			Route::get('/toggleBuy/{id}', 'PurchaseController@toggleBuy');
			Route::get('/toggleBuyAll', 'PurchaseController@toggleBuyAll')->name('toggleBuyAll');//not work if index cart status_bought ==1		
		});
		Route::resource('purchases', 'PurchaseController');

		//Route::get('/verification', ' VerificationController@resend')->name('verification.resend');
		Route::get('/cart', 'PurchaseController@indexCart')->name('cart');
		Route::get('/purchasebuy', 'PurchaseController@buy')->name('purchases.buy');
		Route::get('/purchasesAll/destroy', 'PurchaseController@destroyAll');
		Route::get('/order', 'PurchaseController@order')->name('purchases.order');
	});
	Route::resource('orders', 'OrderController')->only(['index', 'store']);

	Route::get('/verify/{token}', 'OrderController@verify');
});

Route::group(['prefix' => 'admin', 'namespace'=>'Admin', 'middleware' => 'admin'], function(){
	Route::prefix('books')->group(function (){
		Route::get('/toggleDiscontGlB/{id}', 'BooksController@toggleDiscontGlB');
		Route::get('/toggleVisibleGlBAll', 'BooksController@toggleVisibleGlBAll')->name('admin.books.toggleVisibleGlBAll');
		Route::get('/toggleDiscontIdB/{id}', 'BooksController@toggleDiscontIdB');
		Route::get('/toggleVisibleIdBAll', 'BooksController@toggleVisibleIdBAll')->name('admin.books.toggleVisibleIdBAll');
		Route::get('/toggleBookFormat/{id}', 'BooksController@toggleBookFormat');
		Route::get('/toggleSetPublished/{id}', 'BooksController@toggleSetPublished');
	});
	Route::resource('books', 'BooksController')->except(['show']);
	Route::prefix('magazines')->group(function (){
		Route::get('/toggleSubPrice/{id}', 'MagazinesController@toggleSubPrice');
		Route::get('/toggleDiscontGlM/{id}', 'MagazinesController@toggleDiscontGlM');
		Route::get('/toggleVisibleGlMAll', 'MagazinesController@toggleVisibleGlMAll')->name('admin.magazines.toggleVisibleGlMAlll');
		Route::get('/toggleDiscontIdM/{id}', 'MagazinesController@toggleDiscontIdM');
		Route::get('/toggleVisibleIdMAll', 'MagazinesController@toggleVisibleIdMAll')->name('admin.magazines.toggleVisibleIdM');
		Route::get('/toggleSetPublished/{id}', 'MagazinesController@toggleSetPublished');
	});
	Route::resource('magazines', 'MagazinesController')->except(['show']);
	Route::prefix('orders/{order}')->group(function (){
		Route::group(['prefix' => 'purchases'], function(){
			Route::get('/purchasesdaybefore', 'PurchasesController@indexDayBefore')->name('admin.purchases.indexDayBefore');
			Route::get('/purchasesweekbefore', 'PurchasesController@indexWeekBefore')->name('admin.purchases.indexWeekBefore');
			Route::get('/purchasesmonthbefore', 'PurchasesController@indexMonthBefore')->name('admin.purchases.indexMonthBefore');
		});
		Route::resource('purchases', 'PurchasesController')->only(['index']);
	});
	Route::resource('orders', 'OrdersController')->only(['index']);
	Route::resource('users', 'UsersController');
});