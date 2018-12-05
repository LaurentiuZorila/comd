<?php
include './../core/init-calendar.php';

$eventId     = Input::get('eventId');
$employeeId  = Input::get('employeeId');
$eventStatus = Input::get('statusEvent');
$updated     = date('Y-m-d h:i:s');

// Get title off event
$eventData = $customerData->records(Params::TBL_EVENTS, ActionCond::where(['id', $eventId]), ['*'], false);

// Check if for month request are exist in furlough table data. If exist update not insert new
$where = ActionCond::where([
    ['employees_id', $employeeId],
    ['year', $eventData->year],
    ['month', $eventData->start_month],
    ['insert_type', Params::INSERT_TYPE['calendar']]
]);

$whereSecond = ActionCond::where([
    ['employees_id', $employeeId],
    ['year', $eventData->year],
    ['month', $eventData->end_month],
    ['insert_type', Params::INSERT_TYPE['calendar']]
]);

$checkFurloughFirstMonth    = $customerDb->get(Params::TBL_FURLOUGH, $where)->count();
$checkFurloughSecondMonth   = $customerDb->get(Params::TBL_FURLOUGH, $whereSecond)->count();

// Update furlough table
if ($eventStatus == 1 && strtolower($eventData->title) == 'furlough') {
    // Check if we need to insert for one month or for two
    if ($eventData->start_month == $eventData->end_month) {
        if ($checkFurloughFirstMonth < 1) {
            $customerDb->insert(Params::TBL_FURLOUGH, [
                'offices_id'            => $customerUser->officesId(),
                'departments_id'        => $customerUser->departmentId(),
                'employees_id'          => $employeeId,
                'employees_average_id'  => $employeeId . '_' . $eventData->year,
                'insert_type'           => Params::INSERT_TYPE['calendar'],
                'year'                  => $eventData->year,
                'month'                 => $eventData->start_month,
                'quantity'              => $eventData->days_number,
                'days'                  => $eventData->days,
            ]);
        } elseif ($checkFurloughFirstMonth > 0) {
            // If records are present for this month and year, get quantity and sum with new one
            $furloughFromDb = $customerData->records(Params::TBL_FURLOUGH, ActionCond::where([
                ['offices_id', $customerUser->officesId()],
                ['month', $eventData->start_month],
                ['year', $eventData->year]
            ]), ['quantity', 'days', 'id'], false);

            // Sum quantity from Db with new ones
            $quantity = $furloughFromDb->quantity + $eventData->days_number;
            // Concatenate present days with new one
            $days     = empty($furloughFromDb->days) ? $eventData->days : $furloughFromDb->days . ', ' . $eventData->days;

            $customerDb->update(Params::TBL_FURLOUGH, [
                'quantity'              => $quantity,
                'days'                  => $days,
            ], [
                'id'    => $furloughFromDb->id
            ]);
        }

    }
}

// Update tbl events
$update = $customerDb->update(Params::TBL_EVENTS, [
    'event_status'  => Params::EVENTS_STATUS[$eventStatus],
    'status'        => $eventStatus,
    'updated'       => $updated
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

// Update table notification
$notificationEvent = $customerDb->update(Params::TBL_NOTIFICATION, [
    'status'    => $eventStatus,
    'response'  => $notificationResponse,
    'response_status'   => true
], [
    'event_id'  => $eventId
]);



if ($update) {
    $response['response'] = 'Success';
} else {
    $response['response'] = 'Failed';
}

echo json_encode($response);