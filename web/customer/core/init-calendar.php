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
        //see if the file exsists
        if(file_exists($directory.$class_name . '.php'))
        {
            require_once($directory.$class_name . '.php');
            return;
        }
    }

});

$customerUser = new CustomerUser();
$customerData = new CustomerProfile();
$customerDb   = CustomerDB::getInstance();