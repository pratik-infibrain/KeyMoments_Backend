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
if(version_compare(PHP_VERSION, '7.2.0', '>='))
{
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

Route::get('/', 'WelcomeController@index');
Route::get('home', 'HomeController@index');

Route::get('privacy-policy','api\CmsController@PrivacyPolicy');
Route::get('term-conditions','api\CmsController@TermConditions');
Route::get('privacypolicy.html','api\CmsController@PrivacyPolicy');
Route::get('termconditions.html','api\CmsController@TermConditions');
/*
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);*/

Route::get('test', 'TestController@index');

// Authentication routes...
//Route::get('auth/login', 'Auth\AuthController@getLogin');

//Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/login', array('as' => 'auth.login', 'uses'=>'Auth\AuthController@getLogin'));
Route::post('auth/login', array('as' => 'auth.postlogin', 'uses'=>'Auth\AuthController@postLogin'));
Route::get('auth/resetpassword', array('as' => 'auth.resetpassword', 'uses'=>'Auth\AuthController@resetpassword'));
Route::post('auth/resetpassword', array('as' => 'auth.resetpassword', 'uses'=>'Auth\AuthController@Postresetpassword'));
//Route::get('auth/logout', array('as' => 'auth.logout', 'uses'=>'AuthController@getLogout'));

// Registration routes...

    Route::get('auth/register', array('as' => 'auth.register', 'uses'=>'Auth\AuthController@getRegister'));
    Route::post('auth/register', array('as' => 'auth.postRegister', 'uses'=>'Auth\AuthController@postRegister'));
    Route::get('auth/confirmresetpswd/{ids}', array('as' => 'auth.resetconfirmpswd', 'uses' => 'Auth\AuthController@confirmresetpswd'));
    Route::post('auth/resetpasswordform', array('as' => 'auth.resetpasswordform', 'uses' => 'Auth\AuthController@resetpasswordform'));


    Route::group(array('middleware' => 'auth'), function(){
	Route::resource('logout', 'Auth\AuthController@logout');
	//Route::get('/backend', 'AdminDashboardController@index');
	Route::get('backend', array('as' => 'list.backend', 'uses' => 'AdminDashboardController@index'));
	/* Profile */
	Route::get('backend/changepassword', array('as' => 'admin.changepassword', 'uses' => 'AdminProfileController@getReset'));
	Route::post('backend/changepassword', ['as' => 'admin.changepassword','uses' => 'AdminProfileController@postReset']);

    Route::get('backend/getpermission/{id}', array('as' => 'list.getpermission', 'uses'=>'RoleprivilegeController@getPermission'));
    Route::get('backend/roleprivileges', array('as' => 'list.roleprivileges', 'uses'=>'RoleprivilegeController@index'));
    Route::get('backend/roleprivileges/add', array('as' => 'add.roleprivileges', 'uses'=>'RoleprivilegeController@create'));
    Route::post('backend/roleprivileges/add', array('as' => 'add.roleprivileges', 'uses'=>'RoleprivilegeController@store'));
    Route::get('backend/roleprivileges/edit/{id}', array('as' => 'edit.roleprivileges', 'uses'=>'RoleprivilegeController@edit'));
    Route::post('backend/roleprivileges/edit/{id}', array('as' => 'edit.roleprivileges', 'uses'=>'RoleprivilegeController@update'));
    Route::get('backend/roleprivileges/{id}/delete', array('as' => 'delete.roleprivileges', 'uses'=>'RoleprivilegeController@destroy'));
    Route::get('backend/roleprivileges/{id}/active', array('as' => 'active.roleprivileges', 'uses'=>'RoleprivilegeController@setActivate'));
    Route::get('backend/roleprivileges/{id}/inactive', array('as' => 'inactive.roleprivileges', 'uses'=>'RoleprivilegeController@setInactivate'));

    Route::get('backend/role', array('as' => 'list.role', 'uses'=>'RoleController@index'));
    Route::get('backend/role/add', array('as' => 'add.role', 'uses'=>'RoleController@create'));
    Route::post('backend/role/add', array('as' => 'add.role', 'uses'=>'RoleController@store'));
    Route::get('backend/role/edit/{id}', array('as' => 'edit.role', 'uses'=>'RoleController@edit'));
    Route::post('backend/role/edit/{id}', array('as' => 'edit.role', 'uses'=>'RoleController@update'));
    Route::get('backend/role/{id}/delete', array('as' => 'delete.role', 'uses'=>'RoleController@destroy'));
    Route::get('backend/role/{id}/active', array('as' => 'active.role', 'uses'=>'RoleController@setActivate'));
    Route::get('backend/role/{id}/inactive', array('as' => 'inactive.role', 'uses'=>'RoleController@setInactivate'));


    Route::get('backend/enum', array('as' => 'list.enum', 'uses'=>'EnumController@index'));
    Route::get('backend/enum/add', array('as' => 'add.enum', 'uses'=>'EnumController@create'));
    Route::post('backend/enum/add', array('as' => 'add.enum', 'uses'=>'EnumController@store'));
    Route::get('backend/enum/edit/{id}', array('as' => 'edit.enum', 'uses'=>'EnumController@edit'));
    Route::post('backend/enum/edit/{id}', array('as' => 'edit.enum', 'uses'=>'EnumController@update'));
    Route::get('backend/enum/{id}/delete', array('as' => 'delete.enum', 'uses'=>'EnumController@destroy'));
    Route::get('backend/enum/{id}/active', array('as' => 'active.enum', 'uses'=>'EnumController@setActivate'));
    Route::get('backend/enum/{id}/inactive', array('as' => 'inactive.enum', 'uses'=>'EnumController@setInactivate'));

	Route::get('backend/package', array('as' => 'list.Package', 'uses'=>'PackageController@index'));
    Route::get('backend/package/add', array('as' => 'add.Package', 'uses'=>'PackageController@create'));
    Route::post('backend/package/add', array('as' => 'add.Package', 'uses'=>'PackageController@store'));
    Route::get('backend/package/edit/{id}', array('as' => 'edit.Package', 'uses'=>'PackageController@edit'));
    Route::post('backend/package/edit/{id}', array('as' => 'edit.Package', 'uses'=>'PackageController@update'));
    Route::get('backend/package/{id}/delete', array('as' => 'delete.Package', 'uses'=>'PackageController@destroy'));
    Route::get('backend/package/{id}/active', array('as' => 'active.Package', 'uses'=>'PackageController@setActivate'));
    Route::get('backend/package/{id}/inactive', array('as' => 'inactive.Package', 'uses'=>'PackageController@setInactivate'));

	Route::get('backend/mobileapp', array('as' => 'list.mobileapp', 'uses'=>'mobileappController@index'));
    Route::get('backend/mobileapp/add', array('as' => 'add.mobileapp', 'uses'=>'mobileappController@create'));
    Route::post('backend/mobileapp/add', array('as' => 'add.mobileapp', 'uses'=>'mobileappController@store'));
    Route::get('backend/mobileapp/edit/{id}', array('as' => 'edit.mobileapp', 'uses'=>'mobileappController@edit'));
    Route::post('backend/mobileapp/edit/{id}', array('as' => 'edit.mobileapp', 'uses'=>'mobileappController@update'));
    Route::get('backend/mobileapp/{id}/delete', array('as' => 'delete.mobileapp', 'uses'=>'mobileappController@destroy'));
    Route::get('backend/mobileapp/{id}/active', array('as' => 'active.mobileapp', 'uses'=>'mobileappController@setActivate'));
    Route::get('backend/mobileapp/{id}/inactive', array('as' => 'inactive.mobileapp', 'uses'=>'mobileappController@setInactivate'));
    Route::post('backend/mobileapp/pass/{id}', array('as' => 'edit.mobileapp.pass', 'uses'=>'mobileappController@changePassword'));

	Route::get('backend/notification', array('as' => 'list.notification', 'uses'=>'NotificationController@index'));
    Route::get('backend/notification/add', array('as' => 'add.notification', 'uses'=>'NotificationController@create'));
    Route::post('backend/notification/add', array('as' => 'add.notification', 'uses'=>'NotificationController@store'));
    Route::get('backend/notification/edit/{id}', array('as' => 'edit.notification', 'uses'=>'NotificationController@edit'));
    Route::post('backend/notification/edit/{id}', array('as' => 'edit.notification', 'uses'=>'NotificationController@update'));
    Route::get('backend/notification/{id}/delete', array('as' => 'delete.notification', 'uses'=>'NotificationController@destroy'));
    Route::get('backend/notification/{id}/active', array('as' => 'active.notification', 'uses'=>'NotificationController@setActivate'));
    Route::get('backend/notification/{id}/inactive', array('as' => 'inactive.notification', 'uses'=>'NotificationController@setInactivate'));

	Route::get('backend/promotion', array('as' => 'list.promotion', 'uses'=>'promotionController@index'));
    Route::get('backend/promotion/add', array('as' => 'add.promotion', 'uses'=>'promotionController@create'));
    Route::post('backend/promotion/add', array('as' => 'add.promotion', 'uses'=>'promotionController@store'));
    Route::get('backend/promotion/edit/{id}', array('as' => 'edit.promotion', 'uses'=>'promotionController@edit'));
    Route::post('backend/promotion/edit/{id}', array('as' => 'edit.promotion', 'uses'=>'promotionController@update'));
    Route::get('backend/promotion/{id}/delete', array('as' => 'delete.promotion', 'uses'=>'promotionController@destroy'));
    Route::get('backend/promotion/{id}/active', array('as' => 'active.promotion', 'uses'=>'promotionController@setActivate'));
    Route::get('backend/promotion/{id}/inactive', array('as' => 'inactive.promotion', 'uses'=>'promotionController@setInactivate'));

Route::get('backend/tutorial', array('as' => 'list.tutorial', 'uses'=>'TutorialController@index'));
    Route::get('backend/tutorial/add', array('as' => 'add.tutorial', 'uses'=>'TutorialController@create'));
    Route::post('backend/tutorial/add', array('as' => 'add.tutorial', 'uses'=>'TutorialController@store'));
    Route::get('backend/tutorial/edit/{id}', array('as' => 'edit.tutorial', 'uses'=>'TutorialController@edit'));
    Route::post('backend/tutorial/edit/{id}', array('as' => 'edit.tutorial', 'uses'=>'TutorialController@update'));
    Route::get('backend/tutorial/{id}/delete', array('as' => 'delete.tutorial', 'uses'=>'TutorialController@destroy'));
    Route::get('backend/tutorial/{id}/active', array('as' => 'active.tutorial', 'uses'=>'TutorialController@setActivate'));
    Route::get('backend/tutorial/{id}/inactive', array('as' => 'inactive.tutorial', 'uses'=>'TutorialController@setInactivate'));

    /* Page Management  */
    Route::get('backend/page', array('as' => 'list.page', 'uses'=>'PageController@index'));

    Route::get('backend/page/add', array('as' => 'add.page', 'uses'=>'PageController@create'));
    Route::post('backend/page/add', array('as' => 'add.page', 'uses'=>'PageController@store'));
    Route::get('backend/page/edit/{id}', array('as' => 'edit.page', 'uses'=>'PageController@edit'));
    Route::post('backend/page/edit/{id}', array('as' => 'edit.page', 'uses'=>'PageController@update'));
    Route::get('backend/page/{id}/delete', array('as' => 'delete.page', 'uses'=>'PageController@destroy'));
    Route::get('backend/page/{id}/active', array('as' => 'active.page', 'uses'=>'PageController@setActivate'));
    Route::get('backend/page/{id}/inactive', array('as' => 'inactive.page', 'uses'=>'PageController@setInactivate'));

    Route::get('backend/tutor', array('as' => 'list.tutor', 'uses'=>'TutorController@index'));
    Route::get('backend/tutor/add', array('as' => 'add.tutor', 'uses'=>'TutorController@create'));
    Route::get('backend/tutor/excel', array('as' => 'excel.tutor', 'uses'=>'TutorController@excel'));
    Route::post('backend/tutor/add', array('as' => 'add.tutor', 'uses'=>'TutorController@store'));
    Route::get('backend/tutor/edit/{id}', array('as' => 'edit.tutor', 'uses'=>'TutorController@edit'));
    Route::post('backend/tutor/edit/{id}', array('as' => 'edit.tutor', 'uses'=>'TutorController@update'));
    Route::get('backend/tutor/{id}/delete', array('as' => 'delete.tutor', 'uses'=>'TutorController@destroy'));
    Route::get('backend/tutor/{id}/active', array('as' => 'active.tutor', 'uses'=>'TutorController@setActivate'));
    Route::get('backend/tutor/{id}/inactive', array('as' => 'inactive.tutor', 'uses'=>'TutorController@setInactivate'));
     Route::get('backend/tutor/{id}/enable', array('as' => 'enable.tutor', 'uses'=>'TutorController@setEnabled'));
    Route::get('backend/tutor/{id}/disable', array('as' => 'disable.tutor', 'uses'=>'TutorController@setDisabled'));

    Route::get('backend/tutor/documentupload', array('as' => 'list.document', 'uses'=>'TutorController@documentUpload'));
    Route::post('backend/tutor/documentupload/add', array('as' => 'add.tutoruploadddocument', 'uses'=>'TutorController@tutorUploaddDocument'));
    Route::get('backend/tutor/documentupload/add', array('as' => 'add.tutoruploadddocument', 'uses'=>'TutorController@tutorUploaddDocumentAdd'));

    Route::get('backend/tutor/documentupload/edit/{id}', array('as' => 'edit.tutoruploeditdocument', 'uses'=>'TutorController@tutorUploaddDocumentUpdate'));

     Route::post('backend/tutor/documentupload/edit/{id}', array('as' => 'edit.tutoruploeditdocument', 'uses'=>'TutorController@tutorUploaddDocumentEdit'));

     Route::get('backend/tutor/{id}/tutoruplodeletedocument', array('as' => 'delete.tutoruplodeletedocument', 'uses'=>'TutorController@tutorUploDeleteDocument'));


    Route::get('backend/student', array('as' => 'list.student', 'uses'=>'StudentController@index'));
    Route::get('backend/student/add', array('as' => 'add.student', 'uses'=>'StudentController@create'));
    Route::get('backend/student/excel', array('as' => 'excel.student', 'uses'=>'StudentController@excel'));
    Route::post('backend/student/add', array('as' => 'add.student', 'uses'=>'StudentController@store'));
    Route::get('backend/student/edit/{id}', array('as' => 'edit.student', 'uses'=>'StudentController@edit'));
    Route::post('backend/student/edit/{id}', array('as' => 'edit.student', 'uses'=>'StudentController@update'));
    Route::get('backend/student/{id}/delete', array('as' => 'delete.student', 'uses'=>'StudentController@destroy'));
    Route::get('backend/student/{id}/active', array('as' => 'active.student', 'uses'=>'StudentController@setActivate'));
    Route::get('backend/student/{id}/inactive', array('as' => 'inactive.student', 'uses'=>'StudentController@setInactivate'));

    Route::get('backend/student/bookingcourse', array('as' => 'list.booking', 'uses'=>'StudentController@booking'));

    Route::get('backend/course', array('as' => 'list.course', 'uses'=>'CourseController@index'));
    Route::get('backend/course/add', array('as' => 'add.course', 'uses'=>'CourseController@create'));
    Route::post('backend/course/add', array('as' => 'add.course', 'uses'=>'CourseController@store'));
    Route::get('backend/course/edit/{id}', array('as' => 'edit.course', 'uses'=>'CourseController@edit'));
    Route::post('backend/course/edit/{id}', array('as' => 'edit.course', 'uses'=>'CourseController@update'));
    Route::get('backend/course/{id}/delete', array('as' => 'delete.course', 'uses'=>'CourseController@destroy'));
    Route::get('backend/course/{id}/active', array('as' => 'active.course', 'uses'=>'CourseController@setActivate'));
    Route::get('backend/course/{id}/approved', array('as' => 'approved.course', 'uses'=>'CourseController@setApproved'));
    Route::get('backend/course/{id}/disapproved', array('as' => 'disapproved.course', 'uses'=>'CourseController@setDisapproved'));
    Route::get('backend/course/{id}/inactive', array('as' => 'inactive.course', 'uses'=>'CourseController@setInactivate'));
    Route::get('backend/course/enrollstudent', array('as' => 'list.coursestudentlist', 'uses'=>'CourseController@enrollStudent'));
    Route::get('backend/course/{id}/courseenrolldelete', array('as' => 'delete.courseenroll', 'uses'=>'CourseController@destroyCourseenroll'));



    Route::get('backend/topic', array('as' => 'list.topic', 'uses'=>'TopicController@index'));
    Route::get('backend/topic/add', array('as' => 'add.topic', 'uses'=>'TopicController@create'));
    Route::post('backend/topic/add', array('as' => 'add.topic', 'uses'=>'TopicController@store'));
    Route::get('backend/topic/edit/{id}', array('as' => 'edit.topic', 'uses'=>'TopicController@edit'));
    Route::post('backend/topic/edit/{id}', array('as' => 'edit.topic', 'uses'=>'TopicController@update'));
    Route::get('backend/topic/{id}/delete', array('as' => 'delete.topic', 'uses'=>'TopicController@destroy'));
    Route::get('backend/topic/{id}/active', array('as' => 'active.topic', 'uses'=>'TopicController@setActivate'));
    Route::get('backend/topic/{id}/inactive', array('as' => 'inactive.topic', 'uses'=>'TopicController@setInactivate'));

    Route::get('backend/subject', array('as' => 'list.subject', 'uses'=>'SubjectController@index'));
    Route::get('backend/subject/add', array('as' => 'add.subject', 'uses'=>'SubjectController@create'));
    Route::post('backend/subject/add', array('as' => 'add.subject', 'uses'=>'SubjectController@store'));
    Route::get('backend/subject/edit/{id}', array('as' => 'edit.subject', 'uses'=>'SubjectController@edit'));
    Route::post('backend/subject/edit/{id}', array('as' => 'edit.subject', 'uses'=>'SubjectController@update'));
    Route::get('backend/subject/{id}/delete', array('as' => 'delete.subject', 'uses'=>'SubjectController@destroy'));
    Route::get('backend/subject/{id}/active', array('as' => 'active.subject', 'uses'=>'SubjectController@setActivate'));
    Route::get('backend/subject/{id}/inactive', array('as' => 'inactive.subject', 'uses'=>'SubjectController@setInactivate'));


    Route::get('backend/stud_class', array('as' => 'list.myclass', 'uses'=>'StudClassController@index'));
    Route::get('backend/stud_class/add', array('as' => 'add.myclass', 'uses'=>'StudClassController@create'));
    Route::post('backend/stud_class/add', array('as' => 'add.myclass', 'uses'=>'StudClassController@store'));
    Route::get('backend/stud_class/edit/{id}', array('as' => 'edit.myclass', 'uses'=>'StudClassController@edit'));
    Route::post('backend/stud_class/edit/{id}', array('as' => 'edit.myclass', 'uses'=>'StudClassController@update'));
    Route::get('backend/stud_class/{id}/delete', array('as' => 'delete.myclass', 'uses'=>'StudClassController@destroy'));
    Route::get('backend/stud_class/{id}/active', array('as' => 'active.myclass', 'uses'=>'StudClassController@setActivate'));
    Route::get('backend/stud_class/{id}/inactive', array('as' => 'inactive.myclass', 'uses'=>'StudClassController@setInactivate'));

    Route::get('backend/categories', array('as' => 'list.category1', 'uses'=>'CategoryController@index'));
    Route::get('backend/categories/add', array('as' => 'add.category', 'uses'=>'CategoryController@create'));
    Route::post('backend/categories/add', array('as' => 'add.category', 'uses'=>'CategoryController@store'));
    Route::get('backend/categories/edit/{id}', array('as' => 'edit.category', 'uses'=>'CategoryController@edit'));
    Route::post('backend/categories/edit/{id}', array('as' => 'edit.category', 'uses'=>'CategoryController@update'));
    Route::get('backend/categories/{id}/delete', array('as' => 'delete.category', 'uses'=>'CategoryController@destroy'));
    Route::get('backend/categories/{id}/active', array('as' => 'active.category', 'uses'=>'CategoryController@setActivate'));
    Route::get('backend/categories/{id}/inactive', array('as' => 'inactive.category', 'uses'=>'CategoryController@setInactivate'));
    Route::get('backend/getparentcat/{catid}', array('as' => 'add.getcategory', 'uses'=>'CourseController@getparentcat'));


    Route::get('backend/email_templete', array('as' => 'list.email_templete', 'uses'=>'EmailTempleteController@index'));
    Route::get('backend/email_templete/add', array('as' => 'add.email_templete', 'uses'=>'EmailTempleteController@create'));
    Route::post('backend/email_templete/add', array('as' => 'add.email_templete', 'uses'=>'EmailTempleteController@store'));
    Route::get('backend/email_templete/edit/{id}', array('as' => 'edit.email_templete', 'uses'=>'EmailTempleteController@edit'));
    Route::post('backend/email_templete/edit/{id}', array('as' => 'edit.email_templete', 'uses'=>'EmailTempleteController@update'));
    Route::get('backend/email_templete/{id}/delete', array('as' => 'delete.email_templete', 'uses'=>'EmailTempleteController@destroy'));
    Route::get('backend/email_templete/{id}/active', array('as' => 'active.email_templete', 'uses'=>'EmailTempleteController@setActivate'));
    Route::get('backend/email_templete/{id}/inactive', array('as' => 'inactive.email_templete', 'uses'=>'EmailTempleteController@setInactivate'));

	/* admin user  */
	Route::get('backend/admin_user', array('as' => 'list.admin_user', 'uses'=>'AdminUserController@index'));
	Route::get('backend/admin_user/add', array('as' => 'add.admin_user', 'uses'=>'AdminUserController@create'));
	Route::post('backend/admin_user/add', array('as' => 'add.admin_user', 'uses'=>'AdminUserController@store'));
	Route::get('backend/admin_user/view/{id}', array('as' => 'view.admin_user', 'uses'=>'AdminUserController@view'));
    Route::get('backend/admin_user/edit/{id}', array('as' => 'edit.admin_user', 'uses'=>'AdminUserController@edit'));
	Route::post('backend/admin_user/edit/{id}', array('as' => 'edit.admin_user', 'uses'=>'AdminUserController@update'));
	Route::get('backend/admin_user/{id}/delete', array('as' => 'delete.admin_user', 'uses'=>'AdminUserController@destroy'));
	Route::get('backend/admin_user/{id}/active', array('as' => 'active.admin_user', 'uses'=>'AdminUserController@setActivate'));
	Route::get('backend/admin_user/{id}/inactive', array('as' => 'inactive.admin_user', 'uses'=>'AdminUserController@setInactivate'));


	Route::get('backend/app-setting', array('as' => 'edit.apisetting', 'uses' => 'ApiSettingController@edit'));
	Route::post('backend/app-setting', ['as' => 'edit.apisetting','uses' => 'ApiSettingController@update']);
    Route::get('backend/setting', array('as' => 'edit.setting', 'uses' => 'SettingController@edit'));
    Route::post('backend/setting', ['as' => 'edit.setting','uses' => 'SettingController@update']);

    Route::get('backend/promotion', array('as' => 'list.promotion', 'uses'=>'PromotionController@index'));
    Route::get('backend/promotion/add', array('as' => 'add.promotion', 'uses'=>'PromotionController@create'));
    Route::post('backend/promotion/add', array('as' => 'add.promotion', 'uses'=>'PromotionController@store'));
    Route::get('backend/promotion/edit/{id}', array('as' => 'edit.promotion', 'uses'=>'PromotionController@edit'));
    Route::post('backend/promotion/edit/{id}', array('as' => 'edit.promotion', 'uses'=>'PromotionController@update'));
    Route::get('backend/promotion/{id}/delete', array('as' => 'delete.promotion', 'uses'=>'PromotionController@destroy'));
    Route::get('backend/promotion/{id}/active', array('as' => 'active.promotion', 'uses'=>'PromotionController@setActivate'));
    Route::get('backend/promotion/{id}/inactive', array('as' => 'inactive.promotion', 'uses'=>'PromotionController@setInactivate'));
		//Route::get('backend/profile', ['as' => 'auth.profile','middleware' => 'auth','uses' => 'ProfileController@show']);
		//Route::post('backend/profile', array('as' => 'auth.profile', 'uses'=>'ProfileController@updateProfile'));
		Route::get('backend/keymoment', array('as' => 'list.keymoment', 'uses'=>'KeymomentController@index'));
    Route::get('backend/keymoment/add', array('as' => 'add.keymoment', 'uses'=>'KeymomentController@create'));
    Route::post('backend/keymoment/add', array('as' => 'add.keymoment', 'uses'=>'KeymomentController@store'));
    Route::get('backend/keymoment/edit/{id}', array('as' => 'edit.keymoment', 'uses'=>'KeymomentController@edit'));
    Route::post('backend/keymoment/edit/{id}', array('as' => 'edit.keymoment', 'uses'=>'KeymomentController@update'));
    Route::get('backend/keymoment/{id}/delete', array('as' => 'delete.keymoment', 'uses'=>'KeymomentController@destroy'));
    Route::get('backend/keymoment/{id}/active', array('as' => 'active.keymoment', 'uses'=>'KeymomentController@setActivate'));
    Route::get('backend/keymoment/{id}/inactive', array('as' => 'inactive.keymoment', 'uses'=>'KeymomentController@setInactivate'));
});

//Route::post('/api/logins', 'ApiLoginController@index');

Route::get('api/packagelist', array('as' => 'apilist.package', 'uses'=>'api\PackageController@packageList'));
Route::get('api/promotionlist', array('as' => 'apilist.promotion', 'uses'=>'api\PromotionController@promotionlist'));
Route::get('api/tutoriallist', array('as' => 'apilist.tutorial', 'uses'=>'api\TutorialController@tutorialList'));
Route::post('api/normallogin', array('as' => 'apinormallogin.normallogin', 'uses'=>'api\LoginController@normallogin'));
Route::post('api/sociallogin', array('as' => 'apisociallogin.sociallogin', 'uses'=>'api\LoginController@sociallogin'));
Route::post('api/changepassword', array('as' => 'apichangepassword.changepassword', 'uses'=>'api\MemberController@changepassword'));
Route::post('api/forgotpassword', array('as' => 'apiforgotpassword.forgotpassword', 'uses'=>'api\MemberController@forgotpassword'));
Route::post('api/varifiedotp', array('as' => 'apivarifiedotp.varifiedotp', 'uses'=>'api\MemberController@varifiedotp'));
Route::post('api/resetpassword', array('as' => 'apiresetpassword.resetpassword', 'uses'=>'api\MemberController@resetpassword'));
Route::post('api/register', array('as' => 'apiregister.register', 'uses'=>'api\RegisterController@register'));
Route::get('api/getalldropdown', array('as' => 'apigetalldropdown.getalldropdown', 'uses'=>'api\CommonController@getalldropdown'));
Route::post('api/applypromocode', array('as' => 'apiapplypromocode.applypromocode', 'uses'=>'api\PromotionController@applyPromocode'));
Route::post('api/applygiftsoldier', array('as' => 'apiapplygiftsoldier.applygiftsoldier', 'uses'=>'api\PromotionController@applyGiftsoldier'));
Route::post('api/updateprofile/{id}', array('as' => 'apiupdateprorfile.updateprofile', 'uses'=>'api\MemberController@updateProfile'));
Route::post('api/updatepersonal/{id}', array('as' => 'apiupdatepersonal.updatepersonal', 'uses'=>'api\MemberController@updatePersonal'));
Route::post('api/updatechild/{id}', array('as' => 'apiupdatechild.updatechild', 'uses'=>'api\MemberController@updateChild'));
Route::post('api/addchild/{id}', array('as' => 'apiaddchild.addchild', 'uses'=>'api\MemberController@addChild'));
Route::post('api/updateexecutor/{id}', array('as' => 'apiupdateexecutor.updateexecutor', 'uses'=>'api\MemberController@updateExecutor'));
Route::post('api/addexecutor/{id}', array('as' => 'apiaddexecutor.addexecutor', 'uses'=>'api\MemberController@addExecutor'));
Route::post('api/updateeducation/{id}', array('as' => 'apiupdateeducation.updateeducation', 'uses'=>'api\MemberController@updateEducation'));
Route::get('api/userpackagelist/{id}', array('as' => 'apilist.mypackage', 'uses'=>'api\PackageController@userPackageList'));
Route::post('api/deleteuser', array('as' => 'apideleteuser.deleteuser', 'uses'=>'api\MemberController@deleteUser'));
Route::post('api/deletechild', array('as' => 'apideletechild.deletechild', 'uses'=>'api\MemberController@deleteChild'));
Route::post('api/deleteexecutor', array('as' => 'apideleteuser.deleteuser', 'uses'=>'api\MemberController@deleteExecutor'));
Route::post('api/deletegiftsoldier', array('as' => 'apideletegiftsoldier.deletegiftsoldier', 'uses'=>'api\PromotionController@deleteGiftSoldier'));
Route::post('api/updatepackage', array('as' => 'apiupdate.userpackage', 'uses'=>'api\PackageController@updateUserPackage'));
Route::post('api/email_notification', array('as' => 'apiemailnotification.emailnotification', 'uses'=>'api\MemberController@emailNotification'));
Route::post('api/user_email_notification', array('as' => 'apiuseremailnotification.useremailnotification', 'uses'=>'api\MemberController@getUsetEmailNotification'));
Route::get('api/userSuggestPackageList/{id}', array('as' => 'apilist.mypackage', 'uses'=>'api\PackageController@userSuggestPackageList'));
Route::post('api/create-schedule', array('as' => 'apicreateschedule.createschedule', 'uses'=>'api\MessageController@createSchedule'));
Route::get('api/keymomentlist', array('as' => 'apilist.keymomentlist', 'uses'=>'api\KeymomentController@keymomentlist'));
Route::get('api/api-setting', array('as' => 'api.apisetting', 'uses'=>'api\ApiSettingController@getApiSetting'));
Route::post('api/userlist', array('as' => 'list.user', 'uses'=>'api\MemberController@userlist'));
Route::post('api/inspirationallist', array('as' => 'list.inspirational', 'uses'=>'api\MemberController@InspirationalList'));
Route::post('api/friendspirationlist', array('as' => 'list.inspirational', 'uses'=>'api\MemberController@FriendsInspirationalList'));
Route::post('api/messageList', array('as' => 'list.message', 'uses'=>'api\MessageController@messageList'));
Route::post('api/archivemessage', array('as' => 'archive.message', 'uses'=>'api\MessageController@ArchiveMessage'));
Route::post('api/deletearchivemessage', array('as' => 'archive.deletemessage', 'uses'=>'api\MessageController@deleteArchiveMessage'));
Route::post('api/getarchivemessage', array('as' => 'archive.getmessage', 'uses'=>'api\MessageController@getArchiveMessage'));

Route::post('api/deleteschedulemessage', array('as' => 'delete.message', 'uses'=>'api\MessageController@deleteScheduleMessage'));

Route::post('api/tagmessagelist', array('as' => 'list.tagmessage', 'uses'=>'api\MessageController@TagMessageList'));
Route::post('api/sendmessage', array('as' => 'send.message', 'uses'=>'api\MessageController@sendMessage'));
Route::post('api/schedulesendmessage', array('as' => 'send.schedulmessage', 'uses'=>'api\MessageController@scheduleSendMessage'));

Route::post('api/getallmessages', array('as' => 'list.message', 'uses'=>'api\MessageController@_getAllMessages'));
Route::post('api/getscheduleallmessages', array('as' => 'list.message', 'uses'=>'api\MessageController@_getAllScheduleMessages'));

Route::post('api/notificationlist', array('as' => 'list.notificationlist', 'uses'=>'api\CommonController@notificationList'));
Route::post('api/updatenotificationstatus', array('as' => 'update.notificationstatus', 'uses'=>'api\CommonController@updateNotificationStatus'));
Route::post('api/updateusernotification', array('as' => 'update.usernotificationstatus', 'uses'=>'api\CommonController@updateUserNotificationStatus'));
Route::post('api/countnotification', array('as' => 'count.countnotification', 'uses'=>'api\CommonController@getNewNotification'));
Route::post('api/deletenotification', array('as' => 'delete.deletenotification', 'uses'=>'api\MemberController@deleteNotification'));

Route::post('api/updatepairid',  array('as' => 'update.userpairid', 'uses'=>'api\MemberController@updatePairId'));

Route::post('api/inspirationalschedule', array('as' => 'list.inspirationalschedule', 'uses'=>'api\MessageController@inspirationalScheduleList'));
Route::post('api/setfavourite', array('as' => 'send.favmessage', 'uses'=>'api\MessageController@FavMessage'));
Route::post('api/getfavourite', array('as' => 'get.favmessage', 'uses'=>'api\MessageController@getFavMessage'));
Route::post('api/deletefavourite', array('as' => 'delete.favmessage', 'uses'=>'api\MessageController@deleteFavMessage'));

Route::post('api/setinspirational', array('as' => 'send.inspirationalmessage', 'uses'=>'api\MessageController@InspirationalMessage'));
Route::post('api/getinspirational', array('as' => 'get.inspirationalmessage', 'uses'=>'api\MessageController@getInspMessage'));
Route::post('api/deleteinspirational', array('as' => 'delete.inspirationalmessage', 'uses'=>'api\MessageController@deleteInspirationalMessage'));

Route::post('api/archivespecificemessage', array('as' => 'archive.specificemessage', 'uses'=>'api\MessageController@ArchivespecificeMessage'));
Route::post('api/deletearchivespecificemessage', array('as' => 'archive.deletespecificemessage', 'uses'=>'api\MessageController@deleteArchivespecificeMessage'));
Route::post('api/getarchivespecificemessage', array('as' => 'archive.getspecificemessage', 'uses'=>'api\MessageController@getArchiveSpecificeMessage'));

Route::post('api/getuserkeymomentlist', array('as' => 'get.keymomentuser', 'uses'=>'api\KeymomentController@getUserkeymomentList'));
Route::post('api/getuserkeymomentschedulelist', array('as' => 'get.keymomentschedule', 'uses'=>'api\MessageController@getKeymomentScheduleList'));

Route::post('api/getkeymomenttolist', array('as' => 'get.keymomenttolist', 'uses'=>'api\MessageController@getKeymomenttoList'));
Route::post('api/getkeymomentfromlist', array('as' => 'get.keymomentfromlist', 'uses'=>'api\MessageController@getKeymomentfromList'));
Route::post('api/updateunreadschedulemessage', array('as' => 'update.unreadschedulemessage', 'uses'=>'api\MessageController@updateUnreadScheduleMessage'));
Route::post('api/getscheduledetail', array('as' => 'get.schedulebyid', 'uses'=>'api\MessageController@getScheduleDetail'));

Route::post('api/uploadinvitevideo', array('as' => 'upload.invitevideo', 'uses'=>'api\VideoController@inviteUploadVideo'));

Route::post('api/checkemailexist', array('as' => 'chk.emailexist', 'uses'=>'api\MemberController@checkEmailExist'));
Route::post('api/store-paymentdata', array('as' => 'set.paymentdata', 'uses'=>'api\PackageController@storePaymentdata'));
Route::post('api/get-paymentdata', array('as' => 'get.paymentdata', 'uses'=>'api\PackageController@getUserPaymentData'));
Route::post('api/webhook', array('as' => 'set.webhook', 'uses'=>'api\PackageController@webhook'));
#Route::post('api/taggedschedul', array('as' => 'list.taggedschedul', 'uses'=>'api\MessageController@taggedScheduleList'));