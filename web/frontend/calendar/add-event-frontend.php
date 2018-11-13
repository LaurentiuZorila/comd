<?php
require_once './../core/calendar-init.php';

$title  = Input::get('title');
$start  = Input::get('start');
$end    = Input::get('end');
$userId = Input::get('userId');
$customerId = Input::get('customerId');
$added  = date('Y-m-d H:i:s');

$startDate = new DateTime($start);
$endDate   = new DateTime($end);
$difference = $startDate->diff($endDate)->days;


$frontDb->insert(Params::TBL_EVENTS, [
    'user_id'       => $userId,
    'lead_id'       => $customerId,
    'title'         => $title,
    'start'         => $start,
    'end'           => $end,
    'days'          => $difference,
    'accepted'      => false,
    'added'         => $added
]);

?>