<?php
require_once './../core/calendar-init.php';

$title       = Input::get('title');
$eventStatus = Input::get('eventStatus');
$start       = Input::get('start');
$end         = Input::get('end');
$userId      = Input::get('userId');
$customerId  = Input::get('customerId');
$added       = date('Y-m-d H:i:s');

// Number of days
$startDate      = new DateTime($start);
$endDate        = new DateTime($end);
$endDateOneMore = $endDate->modify( '+1 day' );
$totalDays      = $startDate->diff($endDateOneMore)->days;

// End date to insert in Db for fetching fullcalenar.js
$endDateStop    = $endDateOneMore->format('Y-m-d H:i:s');

// Days of month
$interval   = new DateInterval('P1D');
$daterange  = new DatePeriod($startDate, $interval , $endDateOneMore);

foreach($daterange as $range){
    $days[]         = $range->format("d/m");
    $months[]       = $range->format("m");
}

$requestDays    = implode(', ', $days);
$allMonths      = implode(',', $months);
$startMonth     = current($months);
$endMonth       = end($months);


$insertEvent = $frontDb->insert(Params::TBL_EVENTS, [
    'user_id'       => $userId,
    'lead_id'       => $customerId,
    'title'         => $title,
    'event_status'  => $eventStatus,
    'start'         => $start,
    'end'           => $endDateStop,
    'days_number'   => $totalDays,
    'days'          => $requestDays,
    'all_months'    => $allMonths,
    'start_month'   => $startMonth,
    'end_month'     => $endMonth,
    'status'        => 2,
    'added'         => $added
]);

$insertNotificatio = $frontDb->insert(Params::TBL_NOTIFICATION, [
    'user_id'   => $userId,
    'lead_id'   => $customerId,
    'status'    => 2,
    'message'   => 'new_event'
]);

if ($insertEvent) {
    echo json_encode(['added' => 'success']);
} else {
    echo json_encode(['added' => 'failed']);
}

?>