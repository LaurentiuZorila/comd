<?php
require_once 'core/init.php';
require_once '../vendor/league/csv/autoload.php';
use League\Csv\Writer;
//we create the CSV into memory
$csv = Writer::createFromFileObject(new SplTempFileObject());
//we insert the CSV header
$csv->insertOne(['First Name', 'Last Name']);
for ($x=1; $x<10; $x++) {
    $csv->insertAll([['', '']]);
}

// Because you are providing the filename you don't have to
// set the HTTP headers Writer::output can
// directly set them for you
// The file is downloadable
$csv->output('template.csv');
die;



