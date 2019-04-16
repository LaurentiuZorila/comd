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
$common = new BackendProfile();

$departmentsId = Input::get('departments_id');

$offices = $common->records(Params::TBL_OFFICE, AC::where(['departments_id', $departmentsId]), ['id', 'name']);

foreach ($offices as $office) {
    $data[$office->id] = $office->name;
}

if (is_null($data)) {
    $data[] = Translate::t('not_found_offices', ['ucfirst']);
}
echo json_encode($data);