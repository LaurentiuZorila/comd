<?php
function __autoload($class_name)
{
    //commonClasses directories
    $directorys = array(
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

/** Get tables and conditions for tables */
$priorities = Input::get('priorities');
$table      = Input::get('tables');

/** Remove comma if exist at the end of string */
$priorities = Common::checkLastCharacter($priorities);
$table      = Common::checkLastCharacter($table);

/** Transform into array */
$priorities    = explode(',', $priorities);
$table         = explode(',', $table);
$table         = array_map('strtoupper', $table);

/** Combine this two arrays */
$tables_priorities = array_combine($table, $priorities);

echo json_encode($tables_priorities);




