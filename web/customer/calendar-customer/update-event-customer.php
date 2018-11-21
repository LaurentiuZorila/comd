<?php
include './../core/init-calendar.php';

$eventId     = Input::get('eventId');
$employeeId  = Input::get('employeeId');
$eventStatus = Input::get('statusEvent');
$updated     = date('Y-m-d h:i:s');

// Get title off event
$eventData = $customerData->records(Params::TBL_EVENTS, ActionCond::where(['id', $eventId]), ['title', 'days_number', 'start_month', 'end_month', 'year', 'days', 'all_months'], false);


$update = $customerDb->update(Params::TBL_EVENTS, [
    'event_status'  => Params::EVENTS_STATUS[$eventStatus],
    'status'        => $eventStatus,
    'updated'       => $updated
], [
    'id'    => $eventId
]);

//if ($eventStatus == 1 && strtolower($eventData->title) == 'furlough') {
//    if ($eventData->start_month == $eventData->end_month) {
//        $customerDb->insert(Params::TBL_FURLOUGH, [
//            'offices_id'            => $customerUser->officesId(),
//            'departments_id'        => $customerUser->departmentId(),
//            'employees_id'          => $employeeId,
//            'employees_average_id'  => $employeeId . '_' . $eventData->year,
//            'year'                  => $eventData->year,
//            'month'                 => $eventData->start_month,
//            'quantity'              => $eventData->days_number,
//            'days'                  => $eventData->days,
//        ]);
//    }
//}


switch ($eventStatus) {
    case '1':
        $notificationResponse = 'event_response_success';
        break;
    case '3':
        $notificationResponse = 'event_response_denied';
        break;
}

$notificationEvent = $customerDb->update(Params::TBL_NOTIFICATION, [
    'status'    => $eventStatus,
    'response'  => $notificationResponse
], [
    'event_id'  => $eventId
]);



if ($update) {
    $response['response'] = 'Success';
} else {
    $response['response'] = 'Failed';
}

echo json_encode($response);