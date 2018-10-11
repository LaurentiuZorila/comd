<?php
require_once 'core/init.php';
require_once '../vendor/league/csv/autoload.php';

use League\Csv\Writer;

$user   = new CustomerUser();
$data   = new CustomerProfile();

//we fetch the info from a DB using a PDO object
$records = $data->records(Params::TBL_EMPLOYEES, ['offices_id', '=', $user->officesId()], ['id', 'name']);
foreach ($records as $record) {
    $sth[] = (array)$record;
}
//we create the CSV into memory
$csv = Writer::createFromFileObject(new SplTempFileObject());

//we insert the CSV header
$csv->insertOne(['employees_id', 'name', 'quantity']);

// The PDOStatement Object implements the Traversable Interface
// that's why Writer::insertAll can directly insert
// the data into the CSV
$csv->insertAll($sth);

// Because you are providing the filename you don't have to
// set the HTTP headers Writer::output can
// directly set them for you
// The file is downloadable
$csv->output('users.csv');
die;



