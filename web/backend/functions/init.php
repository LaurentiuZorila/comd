<?php
error_reporting(-1);
ini_set('display_errors', 1);
ob_start();
/**
 * Created by PhpStorm.
 * BackendUser: Acasa
 * Date: 3/29/2018
 * Time: 10:29 PM
 */
if (!isset($_SESSION)) {
    session_start();
}
$dbhost ="laur-mysql"; // set the hostname
$dbname ="laur" ; // set the database name
$dbuser ="laur" ; // set the mysql username
$dbpass ="laur";  // set the mysql password
try
{
    $con = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e)
{
    echo 'Connection failed: ' . $e->getMessage();
    $con = null;
}
?>