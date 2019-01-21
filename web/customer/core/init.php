<?php

session_start(); // global settings

spl_autoload_register(function($class_name){
    //commonClasses directories
    $directorys = array(
        'customerClasses/',
        'common/classes/',
        '../customerClasses/',
        '../common/classes/',
        '../../common/classes/',
        '../../customerClasses/'
    );

    //for each directory
    foreach($directorys as $directory)
    {
        //see if the file exist
        if(file_exists($directory.$class_name . '.php'))
        {
            require_once($directory.$class_name . '.php');
            return;
        }
    }

});

require_once './functions/sanitize.php';
$lead       = new CustomerUser();
$leadData   = new CustomerProfile();
$leadDb     = CustomerDB::getInstance();
if (!$lead->isLoggedIn()) {
    Redirect::to('login.php');
} else {
    $lang   = $lead->language();
    $langId = $lead->language(false);
    Session::put('lang', $lang);
}

//if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
//    $hash = Cookie::get(Config::get('remember/cookie_name'));
//    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
//    if ($hashCheck->count()) {
//        //echo 'Hash matches, log user in';
//        $user = new CustomerUser($hashCheck->first()->user_id);
//        $user->login();
//    }
//}
