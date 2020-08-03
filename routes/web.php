<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

use App\Models\Category;
use App\Models\Course;
use App\Setting;
use App\Slider;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group

hjjhhhhhhhhhhhhhhhhhhhhhhhhhhh nmhjkl mnbvghjk nhyuik
jjjo mjukioyhgcvbnnnnm,fg
klxm
gjnbbtygklbhuy
ggytfhnvjy
yyujhj vn nhhyt bht                              66  ggggggggggggggggggg
jjjjj jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj jkuukjhvbmkiu
| contains the "web" middleware group. Now create something great!
cccccccccccccccccccccccccccccccccccccccccccccccccccccccccccgrttryyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyytryryrryryryrttrytryryrtytrryryryttryrhrhtryrtrythtrhtrhtrhhrrhrhrhtrthrthhthrrthrhrthtrryrytytrtytrrrrrrrryrtyyrytrtyrytrrtrtyutryurytryrrrrrrrrytttyyrtrtyyrtyrtyryryyrryryyyyryytrtyyr5757575r457457457555555747575744545455745745555555555555555555555555574554774575747555555455555555577777775555557745777745745747547457455475474574545555555555555554777777777777777777547474576545654654656555555555555555555555555555555555555555555555456
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

Route::post('language/{locale}', function (Request $request,$locale) {
    // App::setLocale($locale);
    $request->session()->put('lang', $locale);
});
Route::get('/home', 'DashboardController@index')->name('home');
Route::get('/site', 'DashboardController@site')->name('site');
Route::get('/', 'DashboardController@site')->name('site');
Route::get('/book-my-session', 'DashboardController@book_session')->name('book-my-session');
Route::get('/blogs', 'DashboardController@site')->name('blogs');
Route::get('/about-us', 'DashboardController@about_us')->name('about-us');
Route::get('/join-us', 'DashboardController@join_us')->name('join-us');
Route::get('/Clinical-Psychologists', 'DashboardController@Clinical_Psychologists')->name('Clinical-Psychologists');
Route::get('/Psychiatrists', 'DashboardController@Psychiatrists')->name('Psychiatrists');
Route::get('/mental-health-volunteers', 'DashboardController@mental_health_volunteers')->name('mental-health-volunteers');
Route::post('add_clinical_psychologists', 'Frontend\CourseController@add_clinical_psychologists')->name('add_clinical_psychologists');
Route::post('add_psychiatrists', 'Frontend\CourseController@add_psychiatrists')->name('add_psychiatrists');
Route::post('add_mental_health_volunteers', 'Frontend\CourseController@add_mental_health_volunteers')->name('add_mental_health_volunteers');
Route::post('booking_session', 'Frontend\CourseController@booking_session')->name('booking_session');
// Route::get('/', function () {
//     $latest = Course::where('status',1)->get()->take(3);
//     $latest2 = Course::where('status',1)->get();
//     $categories = Category::all();
//     $categories1 = Category::take(3)->get();
//     $slider = Slider::all();
//     $slider1 = Slider::where('id', '>=', 1)->first();
//     $latest1 = Course::where('id', '>=', 1)->where('status',1)->first();
//     $setting = Setting::where('id',1)->first();

//     return view('home.frontend.index', compact('slider', 'categories', 'categories1', 'slider1', 'latest', 'latest1','latest2','setting'));
// });
Route::get('/contact-us', function () {


    return view('site.contact_us');
});
/**
 * admin routes
 */
Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['Admin']], function () {

        Route::get('dashboard', 'Backend\DashboardController@dashboard')->name('dashboard');
        Route::get('sales', 'Backend\DashboardController@sell_courses')->name('sales');
        Route::get('settings',  'SettingController@index')->name('settings');
        Route::post('update-settings',  'SettingController@update')->name('update_settings');
        Route::get('all-users', 'Backend\DashboardController@all_users')->name('all-users');
        Route::get('all-trainers', 'Backend\DashboardController@all_trainers')->name('all_trainers');
        Route::get('delete-user', 'Backend\UserController@delete_users')->name('delete_users');
        Route::get('add/user', 'Backend\UserController@new_user_form')->name('new_user_form');
        Route::post('new-profile', 'Backend\UserController@new_profile')->name('admin_new_profile');
        Route::get('edit-profile', 'Backend\UserController@edit')->name('edit_profile');
        Route::post('update-profile', 'Backend\UserController@update_profile')->name('admin_update_profile');




        Route::get('all-accounts', 'Backend\DashboardController@accounts')->name('accounts');

        Route::get('all-courses', 'Backend\DashboardController@courses')->name('courses');
        Route::get('all-Clinical-Psychologists', 'Backend\DashboardController@all_Clinical_Psychologists')->name('all-Clinical-Psychologists');
        Route::get('all-psychiatrists', 'Backend\DashboardController@all_psychiatrists')->name('all-psychiatrists');
        Route::get('all-mental-health-volunteers', 'Backend\DashboardController@all_mental_health_volunteers')->name('all-mental-health-volunteers');
        Route::get('add-course', 'Backend\DashboardController@add_course')->name('add-course');

        Route::post('course/update', 'Backend\DashboardController@course_store')->name('course_store');
        Route::get('course/edit/{id}', 'Backend\DashboardController@course_edit');
        Route::post('course/updated', 'Backend\DashboardController@course_update')->name('update_course');

        Route::get('delete', 'Frontend\CourseController@delete')->name('delete');

        Route::get('change_course_status', 'Backend\DashboardController@change_course_status')->name('change_course_status');

        Route::get('slider', 'Backend\DashboardController@slider')->name('slider');
        Route::post('add-slider-data', 'Backend\DashboardController@sliderdata')->name('slider-data');
        Route::get('edit_slider', 'Backend\DashboardController@edit_slider')->name('slider-edit');
        Route::post('edit-slider-data', 'Backend\DashboardController@update_slider')->name('slider-edit-data');
        Route::get('delete-slider', 'Backend\DashboardController@delete_slider');


        Route::get('currency', 'Backend\CurrencyController@index')->name('currency');
        Route::post('currency/store', 'Backend\CurrencyController@store')->name('save_currency');
        Route::get('edit/currency', 'Backend\CurrencyController@edit');
        Route::post('currency/updates', 'Backend\CurrencyController@update')->name('update_currency');
        Route::get('delete/currency', 'Backend\CurrencyController@destroy');

        Route::get('category', 'Backend\DashboardController@categories')->name('category');
        Route::POST('save_category', 'Frontend\CategoryController@create')->name('save_category');
        Route::get('delete/category', 'Frontend\CategoryController@delete_category')->name('delete_category');
        Route::get('edit/category', 'Frontend\CategoryController@edit')->name('edit_category');
        Route::post('update/category', 'Frontend\CategoryController@update')->name('update_category');





    });
});


//trainer
Route::prefix('trainer')->group(function () {
    Route::group(['middleware' => ['Trainer']], function () {

        Route::get('dashboard', 'Trainer\DashboardController@dashboard')->name('Trainer/dashboard');
        Route::get('my-courses', 'Trainer\DashboardController@courses')->name('my_courses');
        Route::get('sell-courses', 'Trainer\DashboardController@sell_courses')->name('sell_courses');
        Route::get('add-form', 'Frontend\CourseController@show')->name('form');
        Route::post('add-course', 'Frontend\CourseController@create')->name('course_data');
        Route::get('edit-course/{id}', 'Frontend\CourseController@edit')->name('edit-course');
        Route::get('delete', 'Frontend\CourseController@delete')->name('delete');
        Route::post('update-course', 'Frontend\CourseController@update')->name('update-course');
        Route::get('view-lessons/{id}', 'LessonController@index')->name('lessons');
        Route::get('edit-lessons/{id}', 'LessonController@edit')->name('edit_lesson');
        Route::get('add-lesson-form/{id}', 'LessonController@show')->name('form');
        Route::get('edit-lesson-form/{id}', 'LessonController@edit')->name('edit_form');
        Route::post('add-lesson',      'LessonController@store')->name('add_lesson');
        Route::post('update-lesson',   'LessonController@store')->name('update_lesson');
        Route::get('delete-lesson/{id}',    'LessonController@delete')->name('delete');
        Route::get('profile',          'Trainer\DashboardController@profile')->name('profile');
        Route::post('update-profile',  'Trainer\DashboardController@update_profile')->name('update_profile');
        /*trainer page 11*/
        Route::get('trainer-profile', 'Trainer\DashboardController@trainer_profile')->name('trainer_profile');
    });
});


Route::prefix('user')->group(function () {
    Route::group(['middleware' => ['User']], function () {
        Route::get('become_trainer', 'Backend\UserController@become_trainer')->name('become_trainer');
        Route::get('my-course', 'Frontend\CourseController@my_course')->name('my-courses');

    });
});
Route::get('userlogin', 'Frontend\LoginController@userLogin')->name('login-form');
Route::post('login_user', 'Frontend\LoginController@login_user')->name('login_user');
Route::get('user-register', 'Frontend\RegisterController@userRegister')->name('register');
Route::get('all-course', 'Frontend\CourseController@onlineCourse')->name('all-course');
Route::get('course-detail', 'Frontend\CourseController@course_detail')->name('detail-course');
Route::get('offline-course', 'Frontend\CourseController@offlineCourse')->name('offline-course');
Route::get('complete-course', 'Frontend\CourseController@completeCourse')->name('complete-course');
Route::get('homePage',        'Frontend\HomeController@homePage')->name('homePage');

Route::get('progressView',    'progresscontroller@fileUpload')->name('progressView');
Route::post('progressStore',  'progresscontroller@fileStore')->name('progressStore');

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');


Route::get('/home', 'HomeController@index')->name('home');

