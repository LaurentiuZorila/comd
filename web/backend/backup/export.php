<?php
require_once '../backendClasses/Config.php';
$filename = 'db/mydata.sql';
/**
 * MySQL connection configuration
 */
$database	= Config::get('mysql/db');
$user		= Config::get('mysql/username');
$password	= Config::get('mysql/password');
$host       = Config::get('mysql/host');

if (!file_exists($filename)) {
    $filename = $filename .  '_' . time();
}
$fp = @fopen( $filename, 'w+' );
if( !$fp ) {
    echo 'Impossible to create <b>'. $filename .'</b>, please manually create one and assign it full write privileges: <b>777</b>';
    exit;
}
fclose($fp);
$command = 'mysqldump --opt -h '. $host .' -u '. $user .' -p'. $password .' '. $database .' > '. $filename;
exec( $command, $output = array(), $worked );

switch( $worked ) {
    case 0:
        echo 'Database <b>'. $database .'</b> successfully exported to <b>'. $filename .'</b>';
        break;
    case 1:
        echo 'There was a warning during the export of <b>'. $database .'</b> to <b>'. $filename .'</b>';
        break;
    case 2:
        echo 'There was an error during import.'
            . 'Please make sure the import file is saved in the same folder as this script and check your values:'
            . '<br/><br/><table>'
            . '<tr><td>MySQL Database Name:</td><td><b>'. $database .'</b></td></tr>'
            . '<tr><td>MySQL User Name:</td><td><b>'. $user .'</b></td></tr>'
            . '<tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr>'
            . '<tr><td>MySQL Host Name:</td><td><b>'. $host .'</b></td></tr>'
            . '<tr><td>MySQL Import Filename:</td><td><b>'. $filename .'</b></td>'
            . '</tr></table>'
        ;
        break;
}



