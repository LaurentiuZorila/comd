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
$uniqMonths     = array_values(array_unique($months));
//$uniqMonths     = array_values($uniqMonths);
// all days to inset in db
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
    // Begin transaction
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
    // Commit query
    $customerDb->getPdo()->commit();
} catch (Exception $e) {
    $customerDb->getPdo()->rollBack();
}

if ($insertEvent) {

    // Insert data in common tables
    $eventData = $customerData->records(Params::TBL_EVENTS, AC::where(['id', $customerDb->lastId()]), ['*'], false);

    /** Table where need to make changes */
    $table = Params::PREFIX . strtolower($title);
    $where = AC::where([
        ['employees_id', $employeeId],
        ['year', $start_year],
        ['month', $startMonth]
    ]);

// Check if exist records in common table if don't exist add new row
    $countRecords = $customerDb->get($table, $where)->count();

    if ($countRecords > 0) {
        $where1 = AC::where([
            ['user_id', $employeeId],
            ['title', ucfirst($title)],
            ['year', $start_year],
            ['month', $startMonth],
            ['status', 1]
        ]);

        // All days from event table
        $allDaysEvent = $customerDb->get(Params::TBL_EVENTS, $where1, ['days'])->results();

        foreach ($allDaysEvent as $allDays) {
            $eventDays[] = $allDays->days;
        }
        // Sum all days from events table
        $sumDaysEvent = $customerDb->sum(Params::TBL_EVENTS, $where1, 'days_number')->first()->sum;
        $days = implode(',', $eventDays);
        $daysNumber = $sumDaysEvent;
        // Get record id common table
        $records  = $customerDb->get($table, $where, ['id'])->first();
        // Update common table
        $data = $customerDb->update($table,
            [
                'quantity' => $daysNumber,
                'days'     => $days

            ], [
                'id' => $records->id
            ]);

    } else {
        // If not result records add new row with data
        $data = $customerDb->insert($table, [
            'offices_id'            => $customerUser->officesId(),
            'departments_id'        => $customerUser->departmentId(),
            'employees_id'          => $employeeId,
            'employees_average_id'  => $employeeId . '_' . $eventData->year,
            'event_id'              => $eventData->id,
            'year'                  => $eventData->year,
            'month'                 => $eventData->month,
            'quantity'              => $eventData->days_number,
            'days'                  => $eventData->days,
        ]);
    }
}

if ($data) {
    echo 1;
} else {
    echo 0;
}

?>