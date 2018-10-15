<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$api= app('Dingo\Api\Routing\Router');

$api->version('v1',function($api){
	$api->post('authenticate','App\Http\Controllers\Auth\AuthController@authenticate');
  $api->post('authenticate_group','App\Http\Controllers\Auth\AuthController@authenticate_group');
	$api->post('authenticate_admin','App\Http\Controllers\Auth\AuthController@authenticate_admin');
	$api->post('register','App\Http\Controllers\CompanyController@register');
	$api->get('activate/{id}','App\Http\Controllers\CompanyController@activate');
	$api->put('change_logo/{id}','App\Http\Controllers\CompanyController@change_logo');
	$api->put('change_tripadvisor/{id}','App\Http\Controllers\CompanyController@change_tripadvisor');
	$api->put('change_image/{id}','App\Http\Controllers\CompanyController@change_image');
	$api->get('images/{name}/{size}','App\Http\Controllers\CompanyController@images');
	$api->put('user/passwd','App\Http\Controllers\Auth\AuthController@passwd');
	$api->post('upload_slider','App\Http\Controllers\SliderController@store');
	$api->get('generateid','App\Http\Controllers\SettingController@generateId');
	$api->get('home','App\Http\Controllers\ApiController@home');
	$api->get('page_category','App\Http\Controllers\ApiController@category');
	$api->get('page_product','App\Http\Controllers\ApiController@product');
  $api->get('page_accommodation','App\Http\Controllers\ApiController@accommodation');
  $api->get('page_room_detail','App\Http\Controllers\ApiController@room_detail');
	$api->get('page_tags','App\Http\Controllers\ApiController@tags');
	$api->get('page_gallery','App\Http\Controllers\ApiController@gallery');
	$api->get('page_search','App\Http\Controllers\ApiController@search');
	$api->get('pages','App\Http\Controllers\ApiController@pages');
	$api->get('page_blog','App\Http\Controllers\ApiController@blog');
	$api->get('page_blog_detail','App\Http\Controllers\ApiController@blog_detail');
	$api->get('page_contact','App\Http\Controllers\ApiController@contact');
	$api->get('booking','App\Http\Controllers\ApiController@booking');
	$api->post('booking','App\Http\Controllers\BookingController@store');
	$api->post('contact','App\Http\Controllers\ApiController@contact_store');
    $api->put('change_theme_pass/{id}','App\Http\Controllers\CompanyController@change_theme');
    $api->put('install_frontend/{id}','App\Http\Controllers\CompanyController@install_frontend');

    $api->get('booking_page','App\Http\Controllers\ApiController@booking_page');



});

$api->version('v1', ['middleware'=> 'jwt.auth'],function($api){
	//User
	$api->get('auth/user','App\Http\Controllers\Auth\AuthController@user');
	$api->get('userprofile','App\Http\Controllers\Auth\AuthController@user');
	$api->put('userprofile/{id}','App\Http\Controllers\UserController@update');
	$api->put('update_main_image/{id}','App\Http\Controllers\ProductController@update_main_image');
  $api->put('update_main_room_image/{id}','App\Http\Controllers\RoomController@update_main_room_image');
    $api->post('change_featured_image','App\Http\Controllers\PostController@change_featured_image');
    $api->post('upload_pdf','App\Http\Controllers\ReportController@upload_pdf');
    $api->post('report_upload_image','App\Http\Controllers\ReportController@upload_image');
    $api->post('product_upload_image','App\Http\Controllers\ProductCategoryController@upload_image');
    $api->post('upload_image','App\Http\Controllers\AddCreditController@upload_images');
    $api->post('change_area_image','App\Http\Controllers\AreaController@change_area_image');
    $api->post('upload_theme','App\Http\Controllers\ThemeController@upload');
    $api->put('change_theme/{id}','App\Http\Controllers\CompanyController@change_theme');
    $api->put('change_theme_setting/{id}','App\Http\Controllers\CompanyController@change_theme_setting');
    $api->put('update_by_superadmin/{id}','App\Http\Controllers\CompanyController@update_by_superadmin');

	//Master Data
	$api->get('menu_permission','App\Http\Controllers\Auth\AuthController@menu_permission');

  $api->resource('area','App\Http\Controllers\AreaController');
	$api->resource('company','App\Http\Controllers\CompanyController');
  $api->resource('group_company_detail','App\Http\Controllers\GroupCompanyDetailController');
  $api->get('group_company','App\Http\Controllers\CompanyController@group_company');
  $api->post('group_company','App\Http\Controllers\CompanyController@store_group_company');
	$api->resource('category','App\Http\Controllers\CategoryController');
	$api->resource('gallery_category','App\Http\Controllers\GalleryCategoryController');
    $api->resource('product','App\Http\Controllers\ProductController');
    $api->resource('product-category','App\Http\Controllers\ProductCategoryController');
    $api->resource('room','App\Http\Controllers\RoomController');
    $api->resource('user','App\Http\Controllers\UserController');
	$api->resource('role','App\Http\Controllers\RoleController');
	$api->resource('role_list','App\Http\Controllers\RoleController@store_role_list');
	$api->resource('menu','App\Http\Controllers\MenuController');
	$api->resource('slider','App\Http\Controllers\SliderController');
	$api->resource('gallery','App\Http\Controllers\GalleryController');
	$api->resource('facilities','App\Http\Controllers\FacilitiesController');
	$api->resource('product_facilities','App\Http\Controllers\ProductFacilitiesController');
	$api->resource('product_gallery','App\Http\Controllers\ProductGalleryController');
  $api->resource('room_gallery','App\Http\Controllers\RoomGalleryController');
  $api->resource('room_facilities','App\Http\Controllers\RoomFacilitiesController');
  $api->resource('room_tags','App\Http\Controllers\RoomTagsController');
	$api->resource('tags','App\Http\Controllers\TagsController');
	$api->resource('product_tags','App\Http\Controllers\ProductTagsController');
    $api->resource('post_category','App\Http\Controllers\PostCategoryController');
    $api->resource('setting','App\Http\Controllers\SettingController');
    $api->resource('blog','App\Http\Controllers\PostController');
    $api->resource('post_tags','App\Http\Controllers\PostTagsController');
    $api->resource('page','App\Http\Controllers\PostController');
    $api->resource('theme','App\Http\Controllers\ThemeController');
    $api->resource('business_type','App\Http\Controllers\BusinessTypeController');
    $api->resource('testimonial','App\Http\Controllers\TestimonialController');
    $api->resource('report', 'App\Http\Controllers\ReportController');
    $api->resource('report_category','App\Http\Controllers\ReportCategoryController');
    $api->resource('add-credit', 'App\Http\Controllers\AddCreditController');
    $api->resource('add-credit_category','App\Http\Controllers\AddCreditCategoryController');
    $api->resource('add-interest_rate','App\Http\Controllers\InterestRateController');

    //add wisnu tmp
  	$api->resource('rates','App\Http\Controllers\RatesController');
  	$api->resource('currency','App\Http\Controllers\CurrencyController');
  	$api->resource('room_type','App\Http\Controllers\RoomController');






});

//html test
Route::get('direct','BeGeneralController@booking_index');
Route::get('direct/book','BeGeneralController@booking_payment');
Route::post('direct/book','BookingController@post_booking_payment');
Route::get('direct/book/detail/{rf_number}','BeGeneralController@booking_detail');
Route::get('direct/book/confirmation','BeGeneralController@booking_confirmation');

Route::get('todolist','BeGeneralController@todolist');
Route::post('todolist','BeGeneralController@todolistsave');
Route::delete('todolist','BeGeneralController@todolistdelete');


Route::get('/vtweb', 'PagesController@vtweb');

Route::get('/vtdirect', 'PagesController@vtdirect');
Route::post('/vtdirect', 'PagesController@checkout_process');

Route::get('/vt_transaction', 'PagesController@transaction');
Route::post('/vt_transaction', 'PagesController@transaction_process');

Route::post('/vt_notif', 'PagesController@notification');

Route::get('/snap', 'SnapController@snap');
Route::get('/snaptoken', 'SnapController@token');
Route::post('/snapfinish', 'SnapController@finish');

Route::get('/', 'Front\HomeController@index')->name('home.show');

Route::get('tentang-kami', 'Front\AboutController@index')->name('about.show');
Route::get('profil/{id}', 'Front\AboutController@profile')->name('profile.id');

Route::get('berita', 'Front\BlogController@index')->name('blog.show');
Route::get('berita/{id}', 'Front\BlogController@show')->name('blog.id');
Route::get('tag/{id}', 'Front\BlogController@tag')->name('blog.tag');

Route::get('produk', 'Front\ProductController@index')->name('product.show');
Route::get('produk/kategori/{id}', 'Front\ProductController@category')->name('product.category');
Route::get('produk/{id}', 'Front\ProductController@show')->name('product.id');

Route::get('halaman', 'Front\PagesController@index')->name('page.show');
Route::get('halaman/{id}', 'Front\PagesController@show')->name('page.id');

Route::get('laporan/{id}', 'Front\ReportController@index')->name('report.id');
Route::get('laporan/{id}/{detail}', 'Front\ReportController@show')->name('report.detail');

Route::get('kontak', 'Front\ContactController@index')->name('contact.show');
Route::post('kontak', 'Front\ContactController@sendMail')->name('contact.send');

Route::get('simulasi-kredit', 'Front\SimulationController@kredit_show')->name('simulasi.kredit_show');
Route::post('simulasi-kredit', 'Front\SimulationController@index')->name('simulasi.kredit');
Route::get('simulasi-deposito', 'Front\SimulationController@deposito')->name('simulasi.deposito');
Route::post('simulasi-deposito', 'Front\SimulationController@deposito_show')->name('simulasi.deposito_show');
Route::get('simulasi-tabungan', 'Front\SimulationController@deposito')->name('simulasi.tabungan');
Route::post('simulasi-tabungan', 'Front\SimulationController@deposito_show')->name('simulasi.tabungan_show');

Route::get('formulir-pengajuan-kredit-online', 'Front\AddCreditController@index')->name('credit.show');
Route::post('formulir-pengajuan-kredit-online', 'Front\AddCreditController@store')->name('credit.store');