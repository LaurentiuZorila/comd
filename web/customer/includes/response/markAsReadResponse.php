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
$db = CustomerDB::getInstance();
$leadId = Input::get('leadId');
$update = $db->update(Params::TBL_NOTIFICATION,
    [
        'view' => 1
    ], [
        'lead_id'   => $leadId
    ]);

if ($update) {
    echo 1;
} else {
    echo 0;
}