<?php
spl_autoload_register(function($class_name){
    //commonClasses directories
    $directorys = [
        './../customerClasses/',
        './../../common/classes/',
        './../../customerClasses/',
        './../../../common/classes/'
    ];

    //for each directory
    foreach($directorys as $directory) {
        //see if the file exsists
        if(file_exists($directory.$class_name . '.php')) {
            require_once($directory.$class_name . '.php');
            return;
        }
    }
});

$db = CustomerDB::getInstance();
$lead = new CustomerUser();
$statsId = Input::get('statsId');
$employeeId = Input::get('employeeId');
$officeId = Input::get('offices_id');
$departmentsId = Input::get('departments_id');

$update = $db->update(Params::TBL_EMPLOYEES,
    [
        'status' => $statsId
    ],
    [
        'id' => $employeeId
    ]);

if ($update) {
    $addNotification = $db->insert(Params::TBL_NOTIFICATION,
        [
            'user_id'           => $employeeId,
            'lead_id'           => $officeId,
            'departments_id'    => $departmentsId,
            'common'            => 2,
            'supervisors_message'   => 'status_changed',
            'response'              => 'employee_status_changed',
            'response_status'       => 1,
            'date'                  => date('Y-m-d H:m:s'),
        ]);
}

if ($update && $addNotification) {
    echo 1;
} else {
    echo  0;
}