<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//--login--//
$router->post('auth/login', 'LoginController@Login');
//--login--//

$router->group(['middleware' => 'jwt-auth'], function($router)
{ 
    $router->post('logout','LoginController@logOut');
   
    //--admin--//
    $router->group(['middleware' => 'admin-check'], function($router){
        
        //show data
        $router->get('admin/all/user','AdminController@index');
        $router->get('admin/edit/user/info/show/{id}','AdminController@showUserData');
        $router->get('donor/history/{id}','DonotionsHistoryController@index');
        
        //create-update-delete
        $router->post('admin/store/user','AdminController@store');
        $router->post('admin/edit/user/info/{id}','AdminController@update');
        $router->post('admin/edit/user/password/{id}','AdminController@changeUserPassword');
        $router->delete('admin/delete/user/{id}','AdminController@delete');
        
    });
    //--admin--//
    
    //--user--//
    $router->get('/donors/{pagenumber}/{blood_group}','UsersController@index');
    $router->get('user/info','UsersController@userInfo');
    $router->post('update/user','UsersController@update');
    $router->post('update/password','PasswordController@resetPassword');
    $router->post('donotion/date','UsersController@updateDonotionDate');
    //--user--//
    
    
    
});

//--login permission check--//
$router->post('check/email/login/permission','PermissionController@can_user_login');
//--login permission check--//

//--Email--//
$router->post('send/email/verification','SendMailController@sendEmail');
$router->get('varify/email','SendMailController@varifyEmail');
//--Email--//

//--password--//
$router->get('forget/password/email','PasswordController@forgetPasswordSendEmail');
$router->get('change/password/{token}/{id}','PasswordController@approvalToChangePassword');
$router->post('change/possword','PasswordController@changePassword');
//--password--//
$router->get('admin/check','AdminController@adminCheck');
$router->get('user/auth/check','UsersController@authCheck');
$router->get('donotion/history','UsersController@donotiontimeline');




