<?php
function __autoload($class_name)
{
    //commonClasses directories
    $directorys = array(
        '../../customerClasses/',
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

$customerDetails    = new CustomerProfile();
$password           = Input::get('password');
$customerId         = Input::get('id');
$records            = $customerDetails->records(Params::TBL_TEAM_LEAD, ['id', '=', $customerId], ['password'], false)->password;

if (password_verify($password, $records)) {
    $response['Response'] = 'Success';
} else {
    $response['Response'] = 'Failed';
}

echo json_encode($response);