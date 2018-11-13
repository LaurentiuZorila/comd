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

$frontUser    = new FrontendUser();
$frontRecords = new FrontendProfile();
$frontDb      = FrontendDB::getInstance();
