<?php
require_once 'core/init.php';

$user = new CustomerUser();
$user->logout();
Redirect::to('login.php');
