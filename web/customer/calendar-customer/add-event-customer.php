<?php
include './../core/init-calendar.php';

$title  = Input::get('title');
$start  = Input::get('start');
$end    = Input::get('end');
$customerId = Input::get('customerId');
$added  = date('Y-m-d H:i:s');

// Number of days
$startDate = new DateTime($start);
$endDate   = new DateTime($end);
$totalDays = $startDate->diff($endDate)->days;

// Days of month
$interval = new DateInterval('P1D');
$daterange = new DatePeriod($startDate, $interval ,$endDate);

foreach($daterange as $range){
    $days[] = $range->format("d/m");
}
$requestDays = implode(', ', $days);


$insertEvent = $frontDb->insert(Params::TBL_EVENTS, [
    'lead_id'       => $customerId,
    'title'         => $title,
    'start'         => $start,
    'end'           => $end,
    'days_number'   => $totalDays,
    'days'          => $requestDays,
    'status'        => 2,
    'added'         => $added
]);

if ($insertEvent) {
    Session::put('eventAdded', Translate::t($lang, 'Request_success', ['ucfirst' => true]));
}

?>