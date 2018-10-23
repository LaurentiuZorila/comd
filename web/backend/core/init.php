<?php
session_start(); // global settings

spl_autoload_register(function($class_name){

    //commonClasses directories
    $directorys = array(
        'backendClasses/',
        '../common/classes/'
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


//if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
//    $hash = Cookie::get(Config::get('remember/cookie_name'));
//    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
//    if ($hashCheck->count()) {
//        //echo 'Hash matches, log user in';
//        $user = new BackendUser($hashCheck->first()->user_id);
//        $user->login();
//    }
//}
