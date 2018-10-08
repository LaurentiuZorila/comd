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
$tables     = Input::get('tables');
$cond       = Input::get('conditions');
$priorities = Input::get('priority');

/** Remove comma if exist at the end of string */
$tables     = Common::checkLastCharacter($tables);
$cond       = Common::checkLastCharacter($cond);
$priorities = Common::checkLastCharacter($priorities);

/** Transform into array */
$arrayTables        = explode(',', $tables);
$arrayCond          = explode(',', $cond);
$arrayPriorities    = explode(',', $priorities);
$arrayTables        = array_map('strtoupper', $arrayTables);

for ($x=0; $x < count($arrayTables); $x++) {
    $assocArray[$arrayTables[$x]] = [$arrayCond[$x], $arrayPriorities[$x]];
}

echo json_encode($assocArray);




