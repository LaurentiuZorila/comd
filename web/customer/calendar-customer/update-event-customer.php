<?php
include './../core/init-calendar.php';

$eventId     = Input::get('eventId');
$employeeId  = Input::get('employeeId');
$eventStatus = Input::get('statusEvent');
$table       = Input::get('title');
$month       = Input::get('month');
$year        = Input::get('year');



// Update tbl events
$update = $customerDb->update(Params::TBL_EVENTS, [
    'event_status'  => Params::EVENTS_STATUS[$eventStatus],
    'status'        => $eventStatus,
    'updated'       => date("Y-m-d H:i:s")
], [
    'id'    => $eventId
]);

switch ($eventStatus) {
    case '1':
        $notificationResponse = 'event_response_success';
        break;
    case '3':
        $notificationResponse = 'event_response_denied';
        break;
}


if ($update && $eventStatus) {
    $eventData = $customerData->records(Params::TBL_EVENTS, AC::where(['id', $eventId]), ['*'], false);

    /** Table where need to make changes */
    $table = Params::PREFIX . strtolower($table);
    $where = AC::where([
        ['employees_id', $employeeId],
        ['year', $year],
        ['month', $month]
    ]);

// Check if exist records in common table if don't exist add new row
    $countRecords = $customerDb->get($table, $where)->count();

    if ($countRecords > 0) {

        $where1 = AC::where([
            ['user_id', $employeeId],
            ['year', $year],
            ['month', $month],
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
        $updateCommonTbl = $customerDb->update($table,
            [
                'quantity' => $daysNumber,
                'days'     => $days

            ], [
                'id' => $records->id
            ]);

    } else {
        // If not result records add new row with data
        $insert = $customerDb->insert($table, [
            'offices_id'            => $customerUser->officesId(),
            'departments_id'        => $customerUser->departmentId(),
            'employees_id'          => $employeeId,
            'employees_average_id'  => $employeeId . '_' . $eventData->year,
            'insert_type'           => Params::INSERT_TYPE['calendar'],
            'event_id'              => $eventId,
            'year'                  => $eventData->year,
            'month'                 => $eventData->month,
            'quantity'              => $eventData->days_number,
            'days'                  => $eventData->days,
        ]);
    }
}

// Update table notification
$notificationEvent = $customerDb->update(Params::TBL_NOTIFICATION, [
    'status'    => $eventStatus,
    'response'  => $notificationResponse,
    'response_status'   => true,
    'date'      => date("Y-m-d H:i:s")
], [
    'event_id'  => $eventId
]);


if ($update) {
    echo 1;
} else {
    echo 0;
}
