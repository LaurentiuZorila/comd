<?php
session_start(); // global settings

spl_autoload_register(function($class_name){

    //commonClasses directories
    $directorys = array(
        'frontClasses/',
        'common/classes/',
        '../frontClasses/',
        '../common/classes/',
        '../../common/classes/',
        '../../frontClasses/'
    );
    //for each directory
    foreach($directorys as $directory)
    {
        //see if the file exsists
        if(file_exists($directory.$class_name . '.php'))
        {
            require_once($directory.$class_name . '.php');
            return;
        }
    }
});

require_once './functions/sanitize.php';
$frontUser    = new FrontendUser();
$frontProfile = new FrontendProfile();

if (!$frontUser->isLoggedIn()) {
    Redirect::to('login.php');
} else {
    $lang   = $frontUser->language();
    $langId = $frontUser->language(false);
    Session::put('lang', $lang);
}


//if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
//    $hash = Cookie::get(Config::get('remember/cookie_name'));
//    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
//    if ($hashCheck->count()) {
//        //echo 'Hash matches, log user in';
//        $frontUser = new User($hashCheck->first()->user_id);
//        $frontUser->login();
//    }
//}
