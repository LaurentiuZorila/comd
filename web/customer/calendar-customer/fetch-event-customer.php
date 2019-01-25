<?php
include './../core/init-calendar.php';
$leadId  = $customerUser->customerId();
$results = $customerData->records(Params::TBL_EVENTS, AC::where(['lead_id', $leadId]), ['*'], true);

foreach ($results as $key => $result) {
    switch ($result->status) {
        case 1:
            $title = Translate::t($result->title, ['ucfirst']);
            break;
        case 2:
            $title = Translate::t($result->title, ['ucfirst']) . ' - ' . Translate::t($result->event_status, ['ucfirst']);
            break;
        default:
            $title = Translate::t($result->title, ['ucfirst']);
    }
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'userId'    => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => Translate::t(strtolower($title), ['ucfirst']),
            'table'     => $result->title,
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_TITLE_COLORS[$result->title][$result->status],
            'status'    => $result->status,
            'userName'  => $customerData->records(Params::TBL_EMPLOYEES,AC::where(['id', $result->user_id]),['name'], false)->name,
            'month'     => $result->month,
            'year'      => $result->year,
            'totalDays' => $result->days_number,
        ];
}

echo json_encode($event);

?>