<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group
u
| contains the "web" middleware group. Now create something great!
*/

Route::group(['prefix' => 'authentication', 'namespace' => 'Authentication'], function () {
    Route::post('login', 'AuthenticationController@login');
    Route::get('search', 'GuardsController@searchGuard');
    Route::get('degrees', 'DdlController@degrees');
    Route::get('users', 'DdlController@users');
    Route::get('countinents', 'DdlController@countinents');
    Route::get('countries', 'DdlController@countries');
    Route::get('userGroups', 'DdlController@userGroups');
    Route::get('permissions', 'DdlController@permissions');
    Route::get('roles', 'DdlController@roles');
    Route::get('users', 'DdlController@users');
    Route::get('programs', 'DdlController@programs');
    Route::get('universities', 'DdlController@universities');
    Route::get('schools', 'DdlController@schools');
    Route::get('companies', 'DdlController@companies');
    Route::get('students', 'DdlController@students');

});

Route::get('/logout', 'Auth\LoginController@logout');


Route::get('/', 'HomeController@index')->name('home');
