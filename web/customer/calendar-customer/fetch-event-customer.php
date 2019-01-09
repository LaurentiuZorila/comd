<?php
include './../core/init-calendar.php';
$leadId  = $customerUser->customerId();
$results = $customerData->records(Params::TBL_EVENTS, AC::where(['lead_id', $leadId]), ['*'], true);

foreach ($results as $key => $result) {
    if ($result->status == 1) {
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'userId'   => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => Translate::t($result->title, ['ucfirst'=>true]) . ' - ' . Translate::t($result->event_status, ['ucfirst'=>true]),
            'table'     => $result->title,
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_COLORS[$result->status],
            'status'    => $result->status,
            'description' => 'description for Repeating Event',
            'month'     => $result->month,
            'year'      => $result->year
        ];
    } elseif ($result->status == 2) {
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'userId'   => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => Translate::t($result->title, ['ucfirst'=>true]) . ' - ' . Translate::t($result->event_status, ['ucfirst'=>true]),
            'table'     => $result->title,
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_COLORS[$result->status],
            'status'    => $result->status,
            'description' => 'description for Repeating Event',
            'month'     => $result->month,
            'year'      => $result->year
        ];
    } elseif ($result->status == 3) {
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'userId'   => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => Translate::t($result->title, ['ucfirst'=>true]) . ' - ' . Translate::t($result->event_status, ['ucfirst'=>true]),
            'table'     => $result->title,
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_COLORS[$result->status],
            'status'    => $result->status,
            'description' => 'description for Repeating Event',
            'month'     => $result->month,
            'year'      => $result->year
        ];
    }
}

echo json_encode($event);

?>