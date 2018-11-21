<?php
include './../core/init-calendar.php';
$leadId  = $customerUser->customerId();
$results = $customerData->records(Params::TBL_EVENTS, ActionCond::where(['lead_id', $leadId]), ['*'], true);

foreach ($results as $key => $result) {
    if ($result->status == 1) {
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'user_id'   => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => Translate::t($lang, $result->title, ['ucfirst'=>true]) . ' - ' . Translate::t($lang, $result->event_status, ['ucfirst'=>true]),
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_COLORS[$result->status],
            'description' => 'description for Repeating Event',
        ];
    } elseif ($result->status == 2) {
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'user_id'   => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => Translate::t($lang, $result->title, ['ucfirst'=>true]) . ' - ' . Translate::t($lang, $result->event_status, ['ucfirst'=>true]),
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_COLORS[$result->status],
            'description' => 'description for Repeating Event',
        ];
    } elseif ($result->status == 3) {
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'user_id'   => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => Translate::t($lang, $result->title, ['ucfirst'=>true]) . ' - ' . Translate::t($lang, $result->event_status, ['ucfirst'=>true]),
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_COLORS[$result->status],
            'description' => 'description for Repeating Event',
        ];
    }
}

echo json_encode($event);

?>