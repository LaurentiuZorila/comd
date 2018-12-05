<?php
spl_autoload_register(function($class_name){
    //commonClasses directories
    $directorys = array(
        './../customerClasses/',
        './../../common/classes/',
        './../../customerClasses/',
        './../../../common/classes/'
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

