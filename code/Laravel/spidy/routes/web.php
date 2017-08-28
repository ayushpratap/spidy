<?php
use Illuminate\Support\Facades\Input;
use Elasticsearch\ClientBuilder;
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
})->name('home');

//Start the crawler
Route::get('/crawl','crawlController@crawler');
//Route::get('/crawl','testController@crawler');

Route::get('/info',function(){
	return phpinfo();
});
Route::post('/search','testController@search');

Route::get('/search',function(){
	return view('search_page');
});