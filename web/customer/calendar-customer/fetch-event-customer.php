<?php
include './../core/init-calendar.php';
$leadId  = $customerUser->customerId();
$results = $customerData->records(Params::TBL_EVENTS, AC::where(['lead_id', $leadId]), ['*'], true);

foreach ($results as $key => $result) {
    switch ($result->status) {
        case 1:
            $title = Translate::t($result->title, ['ucfirst'=>true]);
            break;
        case 2:
            $title = Translate::t($result->title, ['ucfirst'=>true]) . ' - ' . Translate::t($result->event_status, ['ucfirst'=>true]);
            break;
        default:
            $title = Translate::t($result->title, ['ucfirst'=>true]);
    }
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'userId'    => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => $title,
            'table'     => $result->title,
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_TITLE_COLORS[$result->title][$result->status],
            'status'    => $result->status,
            'description' => 'description for Repeating Event',
            'month'     => $result->month,
            'year'      => $result->year
        ];
}

echo json_encode($event);

?>