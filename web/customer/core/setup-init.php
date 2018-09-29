<?php
session_start(); // global settings

function __autoload($class_name)
{
    //commonClasses directories
    $directorys = [
        './../customerClasses/',
        './../../common/classes/'
    ];

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
}

require_once './../functions/sanitize.php';