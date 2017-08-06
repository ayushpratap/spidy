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


//Route for index page
Route::get('/',function(){
	return view('welcome');
});

//Start the crawler
Route::get('/crawl','crawlController@crawler');
//Route::get('/crawl','testController@crawler');

Route::get('/phpinfo','testController@phpinfo');
Route::get('/search_page',function(){
	return view('search_page');
});