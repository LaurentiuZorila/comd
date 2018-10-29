<?php
require_once 'core/init.php';

$langId   = Input::get('lang');

$user->update('cmd_users', [
    'lang'  => $langId
    ],
    [
        'id' => $user->customerId()
    ]);
Redirect::to('index.php');
exit;
?>