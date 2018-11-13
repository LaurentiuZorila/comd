<?php
require_once 'core/init.php';

$langId   = Input::get('lang');

$frontUser->update('cmd_employees', [
    'lang'  => $langId
    ],
    [
        'id' => $frontUser->userId()
    ]);
Redirect::to('index.php');
exit;
?>