<?php
include './../core/init-calendar.php';

$title       = Input::get('title');
$eventStatus = Input::get('statusEvent');
$start       = Input::get('start');
$end         = Input::get('end');
$userId      = Input::get('userId');
$employeeId  = Input::get('employeeId');


// Number of days
$startDate      = new DateTime($start);
$endDate        = new DateTime($end);
$endDateOneMore = $endDate->modify( '+1 day' );
$totalDays      = $startDate->diff($endDateOneMore)->days;

// End date to insert in Db for fetching fullcalenar.js
$endDateStop    = $endDateOneMore->format('Y-m-d');
$newStart       = $startDate->format('Y-m-d');

// Days of month
$interval   = new DateInterval('P1D');
$daterange  = new DatePeriod($startDate, $interval , $endDateOneMore);

foreach($daterange as $range){
    $days[]             = $range->format("d/m");
    $months[]           = $range->format("m");
    $monthsAndDays[]    = [$range->format("m") => $range->format("d/m")];
    $year[]             = $range->format("Y");
    $monthsAndDaysDb[]  = [$range->format("m") => $range->format("Y-m-d")];
}

// array uniq with months, no duplicates
$uniqMonths     = array_unique($months);
$uniqMonths     = array_values($uniqMonths);
// alll days to inset in db
$requestDays    = implode(', ', $days);
// first month
$startMonth     = current($months);
// last month
$endMonth       = end($months);
//count years
$countYears = count(array_unique($year));
$years      = array_values(array_unique($year));

// Year
if ($countYears > 1) {
    $start_year = $years[0];
    $end_year   = $years[1];
} else {
    $start_year = $years[0];
    $end_year   = $years[0];
}

try {
    $customerDb->getPdo()->beginTransaction();

    // Insert notification in TBL
    $insertNotification = $customerDb->insert(Params::TBL_NOTIFICATION, [
        'event_id'        => $customerDb->lastId(),
        'user_id'         => $employeeId,
        'lead_id'         => $userId,
        'status'          => 1,
        'response'        => 'new_event_added',
        'response_status' => 1,
        'date'            => date('Y-m-d H:i:s')
    ]);

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
        foreach ($monthsAndDaysDb as $key => $value) {
            foreach ($value as $k => $v) {
                if ($k == $uniqMonths[0]) {
                    $firstMonthDays[] = $v;
                } elseif ($k == $uniqMonths[1]) {
                    $secondMonthDays[] = $v;
                }
            }
        }
        $startDayFirstMonth     = $firstMonthDays[0];
        $endDayFirstMonth       = end($firstMonthDays);
        $startDaySecondMonth    = $secondMonthDays[0];
        $endDaySecondMonth      = end($secondMonthDays);

        $endDayFirstMonth   = new DateTime($endDayFirstMonth);
        $endDaySecondMonth  = new DateTime($endDaySecondMonth);

        $endDayFirstMonth   = $endDayFirstMonth->modify( '+1 day' );
        $endDaySecondMonth  = $endDaySecondMonth->modify( '+1 day' );
        $endDayFirstMonth   = $endDayFirstMonth->format('Y-m-d');
        $endDaySecondMonth  = $endDaySecondMonth->format('Y-m-d');

        // if count months are > 1 then count how days are in first month
        $countDaysFirstMonth    = count($uniqMonths) > 1 ? count($daysFirstMonth) : $totalDays;
        // if count months are > 1 then count how days are in second month
        $countDaysSecondMonth   = count($uniqMonths) > 1 ? count($daysSecondMonth) : 0;
        // if count months are > 1 then get days from first month
        $days_first_month       = count($uniqMonths) > 1 ? implode(',', $daysFirstMonth) : implode(', ', $days);
        // if count months are > 1 then get days from second month
        $days_second_month      = count($uniqMonths) > 1 ? implode(',', $daysSecondMonth) : '' ;

        // Insert event in TBL
        $insertEvent = $customerDb->insert(Params::TBL_EVENTS, [
            'user_id'       => $employeeId,
            'lead_id'       => $userId,
            'title'         => ucfirst($title),
            'event_status'  => Params::EVENTS_STATUS[$eventStatus],
            'start'         => $startDayFirstMonth,
            'end'           => $endDayFirstMonth,
            'days_number'   => $countDaysFirstMonth,
            'days'          => implode(',', $daysFirstMonth),
            'month'         => $startMonth,
            'year'          => $start_year,
            'status'        => $eventStatus,
            'added'         => date('Y-m-d H:i:s'),
            'updated'       => date('Y-m-d H:i:s')
        ]);
        $insertSecondEvent = $customerDb->insert(Params::TBL_EVENTS, [
            'user_id'       => $employeeId,
            'lead_id'       => $userId,
            'title'         => ucfirst($title),
            'event_status'  => Params::EVENTS_STATUS[$eventStatus],
            'start'         => $startDaySecondMonth,
            'end'           => $endDaySecondMonth,
            'days_number'   => $countDaysSecondMonth,
            'days'          => implode(',', $daysSecondMonth),
            'month'         => $endMonth,
            'year'          => $end_year,
            'status'        => $eventStatus,
            'added'         => date('Y-m-d H:i:s'),
            'updated'       => date('Y-m-d H:i:s')
        ]);
    } else {
        // Insert event in TBL
        $insertEvent = $customerDb->insert(Params::TBL_EVENTS, [
            'user_id'       => $employeeId,
            'lead_id'       => $userId,
            'title'         => ucfirst($title),
            'event_status'  => Params::EVENTS_STATUS[$eventStatus],
            'start'         => $newStart,
            'end'           => $endDateStop,
            'days_number'   => $totalDays,
            'days'          => $requestDays,
            'month'         => $startMonth,
            'year'          => $start_year,
            'status'        => $eventStatus,
            'added'         => date('Y-m-d H:i:s'),
            'updated'       => date('Y-m-d H:i:s')
        ]);
    }
    $customerDb->getPdo()->commit();
} catch (Exception $e) {
    $customerDb->getPdo()->rollBack();
}

if ($insertEvent) {
    echo 1;
} else {
    echo 0;
}
?>