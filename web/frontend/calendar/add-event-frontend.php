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
    $monthsAndDays[]  = [$range->format("m") => $range->format("d/m")];
    $year[]         = $range->format("Y");
}

// array uniq with months, no duplicates
$uniqMonths     = array_unique($months);
// alll days to inset in db
$requestDays    = implode(', ', $days);
// all months
$allMonths      = implode(',', $uniqMonths);
// first month
$startMonth     = current($months);
// last month
$endMonth       = end($months);
// Year
$years          = $year[0];


if (count($uniqMonths) > 1) {
    foreach ($monthsAndDays as $key => $value) {
        foreach ($value as $k => $v) {
            if ($k == $uniqMonths[0]) {
                $daysFirstMonth[] = $v;
            } elseif ($k == $uniqMonths[1]) {
                $daysSecondMonth[] = $v;
            }
        }
    }
}
// if count months are > 1 then count how day are in first month
$countDaysFirstMonth    = count($uniqMonths) > 1 ? count($daysFirstMonth) : $totalDays;
// if count months are > 1 then count how day are in second month
$countDaysSecondMonth   = count($uniqMonths) > 1 ? count($daysSecondMonth) : 0;
// if count months are > 1 then get days from first month
$days_first_month       = count($uniqMonths) > 1 ? implode(',', $daysFirstMonth) : implode(', ', $days);
// if count months are > 1 then get days from second month
$days_second_month      = count($uniqMonths) > 1 ? implode(',', $daysSecondMonth) : '' ;


// Insert event in TBL
$insertEvent = $frontDb->insert(Params::TBL_EVENTS, [
    'user_id'       => $userId,
    'lead_id'       => $customerId,
    'title'         => $title,
    'event_status'  => $eventStatus,
    'start'         => $start,
    'end'           => $endDateStop,
    'days_number'   => $totalDays,
    'days'          => $requestDays,
    'count_days_first_m'    => $countDaysFirstMonth,
    'count_days_second_m'   => $countDaysSecondMonth,
    'days_first_month'      => $days_first_month,
    'days_second_month'     => $days_second_month,
    'all_months'    => $allMonths,
    'start_month'   => $startMonth,
    'end_month'     => $endMonth,
    'year'          => $years,
    'status'        => 2,
    'added'         => $added
]);

// Insert notification in TBL
$insertNotification = $frontDb->insert(Params::TBL_NOTIFICATION, [
    'event_id'  => $frontDb->lastId(),
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