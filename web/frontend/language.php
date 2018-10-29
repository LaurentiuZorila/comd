<?php
require_once 'core/init.php';

$langId   = Input::get('lang');

$user->update('cmd_employees', [
    'lang'  => $langId
    ],
    [
        'id' => $user->userId()
    ]);
Redirect::to('index.php');
exit;
?>