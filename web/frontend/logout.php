<?php
require_once 'core/init.php';

$user = new FrontendUser();
$user->logout();
header('Location: login.php');
Redirect::to('login.php');
exit;