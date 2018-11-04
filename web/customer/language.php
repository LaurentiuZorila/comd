<?php
require_once 'core/init.php';

$langId   = Input::get('lang');

$lead->update('cmd_users', [
    'lang'  => $langId
    ],
    [
        'id' => $lead->customerId()
    ]);
Redirect::to('index.php');
exit;
?>