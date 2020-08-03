<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'authentication', 'namespace' => 'Authentication'], function () {
    Route::post('login', 'AuthenticationController@login');
    Route::get('search', 'GuardsController@searchGuard');
    Route::get('degrees', 'DdlController@degrees');
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
//  Route::get('degrees', 'GuardsController@degrees');
    Route::get('getGuardForExtraHour', 'GuardsController@getGuardForExtraHour');
    Route::get('mergedOptions', 'GuardsController@mergedOptions');
    Route::get('blackListedGuards', 'GuardsController@blackListedGuards');
    Route::get('softDeletedGuardList', 'GuardsController@softDeletedGuardList');
    Route::get('getClients', 'GuardsController@getClients');


    Route::get('logout', 'AuthenticationController@logout');
    Route::get('forgetPassword', 'AuthenticationController@forgetPassword');
    Route::post('generateNewPasswordMail', 'AuthenticationController@generateNewPasswordMail');
    Route::get('verifyResetPasswordLink/{verificationToken}', 'AuthenticationController@verifyResetPasswordLink');
    Route::post('saveNewPassword', 'AuthenticationController@saveNewPassword');


});
