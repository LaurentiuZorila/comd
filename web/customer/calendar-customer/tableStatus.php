<?php
/**
 * Created by PhpStorm.
 * User: onetwist
 * Date: 2018-12-19
 * Time: 11:35
 */
include './../core/init-calendar.php';
$lead       = new CustomerUser();
$leadData   = new CustomerProfile();

$eventMonth  = Input::post('event_month');
$eventStatus = Input::post('event_status');

if (!empty($eventMonth) && !empty($eventStatus)) {
    if ($eventStatus != 'all') {
        $where = AC::where([
            ['lead_id', $lead->customerId()],
            ['status', $eventStatus],
            ['start_month', $eventMonth],
            ['lead_id', $lead->customerId()],
            ['status', $eventStatus],
            ['end_month', $eventMonth]
        ], ['AND', 'AND', 'OR', 'AND', 'AND']);
    } elseif ($eventStatus == 'all') {
        $where = AC::where([
            ['lead_id', $lead->customerId()],
            ['start_month', $eventMonth],
            ['lead_id', $lead->customerId()],
            ['end_month', $eventMonth]
        ], ['AND', 'OR', 'AND']);
    } else {
        $where = AC::where([
            ['lead_id', $lead->customerId()],
            ['start_month', $eventMonth],
            ['lead_id', $lead->customerId()],
            ['end_month', $eventMonth]
        ], ['AND', 'OR', 'AND']);
    }
} else {
    if ($eventStatus !== 'all') {
        $where = AC::where([
            ['lead_id', $lead->customerId()],
            ['status', $eventStatus],
        ]);
    } elseif ($eventStatus == 'all') {
        $where = AC::where([
            'lead_id', $lead->customerId()
        ]);
    }
}

$allEvents = $leadData->records(Params::TBL_EVENTS, $where, ['*'], true);
?>

<table class="table">
    <thead>
    <tr role="row">
        <th class="text-primary">Name</th>
        <th class="text-primary">Date</th>
        <th class="text-primary">Status</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (count($allEvents) > 0) {
        foreach ($allEvents as $allEvent) { ?>
            <tr>
                <td>
                    <p data-toggle="tooltip" data-placement="top" title="<?php echo $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $allEvent->user_id]), ['name'], false)->name; ?>">
                        <?php echo Common::makeAvatar($leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $allEvent->user_id]), ['name'], false)->name); ?>
                    </p>
                </td>
                <td class="text-small">
                    <?php
                    $collapseData = $allEvent->status == 2 ? 'data-target=#collapseExample' . $allEvent->id . ' aria-controls=collapseExample' . $allEvent->id : '';
                    ?>
                    <a class="" style="cursor: pointer;" type="button" data-toggle="collapse" <?php echo $collapseData; ?> aria-expanded="false">
                        <?php
                        $all_days = explode(',', $allEvent->days);
                        if (count($all_days) > 1) {
                            echo current($all_days) . ' - ' . end($all_days);
                        } else {
                            echo $all_days[0];
                        }
                        ?>
                    </a>
                </td>
                <td>
                    <span class="badge badge-<?php echo Params::EVENTS_STATUS_COLORS[$allEvent->status]; ?>"><?php echo Params::EVENTS_STATUS[$allEvent->status]; ?></span>
                    <div class="collapse" id="collapseExample<?php echo $allEvent->id; ?>">
                        <div class="btn-group btn-group-sm mt-3" role="group" aria-label="Basic example">
                            <a type="" class="btn-sm btn-primary p-1 eventAction" style="cursor: pointer;" id="accepted" data-accepted="1" data-employee="<?php echo $allEvent->user_id; ?>" data-eventid="<?php echo $allEvent->id; ?>" data-title="<?php echo $allEvent->title; ?>" data-month="<?php echo $allEvent->month; ?>" data-year="<?php echo $allEvent->year; ?>"><small>Accept</small></a>
                            <a type="" class="btn-sm btn-danger p-1 ml-2 eventAction" style="cursor: pointer;" id="declined" data-accepted="3" data-employee="<?php echo $allEvent->user_id; ?>" data-eventid="<?php echo $allEvent->id; ?>" data-title="<?php echo $allEvent->title; ?>"><small>Decline</small></a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php }
    } ?>
    </tbody>
</table>
