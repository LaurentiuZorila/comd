<?php
require_once 'core/init.php';

$langId   = Input::get('lang');

$backendUser->update('cmd_supervisors', [
    'lang'  => $langId
    ],
    [
        'id' => $backendUser->userId()
    ]);
Redirect::to('index.php');
exit;
?>