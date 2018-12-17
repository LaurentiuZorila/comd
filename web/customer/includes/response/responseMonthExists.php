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
$leadData = new CustomerProfile();

$officesId = Input::get('officeId');
$month     = Input::get('month');
$year      = Input::get('year');
$table     = Input::get('tables');
$months    = $leadData->records(Params::PREFIX . $table, ActionCond::where([['offices_id', $officesId], ['year', $year]]), ['month']);
foreach ($months as $dbMonth) {
    $allMonths[] = $dbMonth->month;
    // Months from database
    $allMonths = array_unique($allMonths);
}

if (!in_array($month, $allMonths)) {
    $response['Response']   = 'Success';
} else {
    $response['Response']   = 'Failed';
}

echo json_encode($response);