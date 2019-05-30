<?php
include "../../functions/autoload_ajax.php";

$common     = new BackendProfile();
$officeId   = $_GET['office_id'];

$allTables  = $common->records(Params::TBL_OFFICE, ['id', '=', $officeId], ['tables'], false);

$keyTable = explode(',', trim($allTables->tables));
$valTable = explode(',', strtoupper($allTables->tables));
$tables = array_combine($keyTable, $valTable);


echo json_encode($tables);