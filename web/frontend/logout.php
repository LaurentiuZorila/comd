<?php
require_once 'core/init.php';

$user = new User();
$user->logout();
header('Location: login.php');
Redirect::to('login.php');
exit;