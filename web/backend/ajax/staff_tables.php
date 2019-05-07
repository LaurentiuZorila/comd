<?php
spl_autoload_register(function($class_name){
    //commonClasses directories
    $directorys = array(
        './backendClasses/',
        './common/classes/',
        './../backendClasses/',
        './../common/classes/',
        './../../backendClasses/',
        './../../common/classes/',
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
$records    = new BackendProfile();
$officeId   = Input::get('office_id');
$allTables  = $records->records(Params::TBL_OFFICE, AC::where(['id', $officeId]), ['tables'], false);
$tables = explode(',', trim($allTables->tables));
foreach ($tables as $table) {
    $data[$table] = in_array($table, Params::TBL_COMMON) ? Translate::t($table,['ucfirst']) : ucfirst($table);
}

echo json_encode($data);