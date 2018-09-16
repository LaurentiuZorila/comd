<?php

session_start(); // global settings

spl_autoload_register(function($class) {
    require_once 'classes/'. $class . '.php';
});

require_once './functions/sanitize.php';



//if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
//    $hash = Cookie::get(Config::get('remember/cookie_name'));
//    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
//    if ($hashCheck->count()) {
//        //echo 'Hash matches, log user in';
//        $user = new User($hashCheck->first()->user_id);
//        $user->login();
//    }
//}
