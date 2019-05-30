<?php
function __autoload($class_name)
{
    /** CommonClasses directories */
    $directorys = array(
        '../../backendClasses/',
        '../../../common/classes/'
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
}