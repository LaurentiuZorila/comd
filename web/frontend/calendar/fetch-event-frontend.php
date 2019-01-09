<?php
require_once './../core/calendar-init.php';
$userId = $frontUser->userId();
$results = $frontRecords->records(Params::TBL_EVENTS, AC::where(['user_id', $userId]), ['*'], true);

foreach ($results as $key => $result) {
    if ($result->status == 1) {
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'user_id'   => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => Translate::t($result->title, ['ucfirst'=>true]) . ' - ' . Translate::t($result->event_status, ['ucfirst'=>true]),
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_COLORS[$result->status],
            'status'    => $result->status
        ];
    } elseif ($result->status == 2) {
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'user_id'   => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => Translate::t($result->title, ['ucfirst'=>true]) . ' - ' . Translate::t($result->event_status, ['ucfirst'=>true]),
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_COLORS[$result->status],
            'status'    => $result->status
        ];
    } elseif ($result->status == 3) {
        $event[] = [
            'id'        => $result->id,
            'days'      => $result->days,
            'user_id'   => $result->user_id,
            'lead_id'   => $result->lead_id,
            'title'     => Translate::t($result->title, ['ucfirst'=>true]) . ' - ' . Translate::t($result->event_status, ['ucfirst'=>true]),
            'start'     => $result->start,
            'end'       => $result->end,
            'color'     => Params::EVENTS_COLORS[$result->status],
            'status'    => $result->status
        ];
    }
}

echo json_encode($event);

?>