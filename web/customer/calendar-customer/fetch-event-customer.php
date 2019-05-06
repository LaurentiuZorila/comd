<?php
include './../core/init-calendar.php';
$officeId = $customerUser->officesId();
$results = $customerData->records(Params::TBL_EVENTS, AC::where(['lead_id', $officeId]), ['*'], true);

foreach ($results as $key => $result) {
    switch ($result->status) {
        case 1:
            $display = 'none';
            $title = Translate::t(strtolower($result->title), ['ucfirst']);
            break;
        case 2:
            $display = 'block';
            $title = Translate::t(strtolower($result->title), ['ucfirst']) . ' - ' . Translate::t($result->event_status, ['ucfirst']);
            break;
        default:
            $display = 'block';
            $title = Translate::t(strtolower($result->title), ['ucfirst']);
    }
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'userId'    => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => $title . ' - ' . $customerData->records(Params::TBL_EMPLOYEES,AC::where(['id', $result->user_id]),['name'], false)->name,
            'table'     => $result->title,
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_TITLE_COLORS[$result->title][$result->status],
            'status'    => $result->status,
            'userName'  => $customerData->records(Params::TBL_EMPLOYEES,AC::where(['id', $result->user_id]),['name'], false)->name,
            'month'     => $result->month,
            'year'      => $result->year,
            'totalDays' => $result->days_number,
            'statusName'    => Translate::t($result->event_status, ['ucfirst']),
            'titleForModal' => Translate::t(strtolower($result->title), ['ucfirst']),
            'modalButton'   => $display,
        ];
}
echo json_encode($event);

?>