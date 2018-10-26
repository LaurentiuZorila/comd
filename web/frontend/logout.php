<?php
require_once 'core/init.php';
$user = new FrontendUser();
$user->logout();
exit;