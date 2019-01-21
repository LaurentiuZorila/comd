<?php
require_once 'core/init.php';

$langId   = Input::get('lang');

$lead->update('cmd_users', [
    'lang'  => $langId
    ],
    [
        'id' => $lead->customerId()
    ]);
$lang   = $lead->language();
$langId = $lead->language(false);
Session::put('lang', $lang);
Redirect::to('index.php');
exit;
?>