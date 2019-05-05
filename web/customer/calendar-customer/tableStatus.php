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
            ['lead_id', $lead->officesId()],
            ['status', $eventStatus],
            ['month', $eventMonth]
        ]);
    } elseif ($eventStatus == 'all') {
        $where = AC::where([
            ['lead_id', $lead->officesId()],
            ['month', $eventMonth]
        ]);
    } else {
        $where = AC::where([
            ['lead_id', $lead->officesId()],
            ['month', $eventMonth]
        ]);
    }
} else {
    if ($eventStatus != 'all') {
        $where = AC::where([
            ['lead_id', $lead->officesId()],
            ['status', $eventStatus],
        ]);
    } elseif ($eventStatus == 'all') {
        $where = AC::where([
            'lead_id', $lead->officesId()
        ]);
    }
}
$allEvents = $leadData->records(Params::TBL_EVENTS, $where, ['*'], true);
?>


<table class="table">
    <thead>
    <tr role="row">
        <th class="text-white-50" style="margin: 1px; padding-left: 0px; padding-right: 0px;"><?php echo Translate::t('Request', ['ucfirst']); ?></th>
        <th class="text-white-50" style="margin: 1px; padding-left: 0px; padding-right: 0px;"><?php echo Translate::t('Date', ['ucfirst']); ?></th>
        <th class="text-white-50" style="margin: 1px; padding-left: 0px; padding-right: 0px;"><?php echo Translate::t('Status', ['ucfirst']); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (count($allEvents) > 0) {
        foreach ($allEvents as $allEvent) {
            $employeeName = $leadData->records(Params::TBL_EMPLOYEES, AC::where(['id', $allEvent->user_id]), ['name'], false)->name;
            $employeeName = Common::nameForRequestTable($employeeName);
            ?>
            <tr>
                <td class="text-nowrap" style="margin: 1px; padding-left: 0px; padding-right: 0px;">
                    <?php
                    $collapseData = $allEvent->status == 2 ? 'data-target=#collapseExample' . $allEvent->id . ' aria-controls=collapseExample' . $allEvent->id : '';
                    ?>
                    <span class="badge" data-toggle="collapse" <?php echo $collapseData; ?> aria-expanded="false" style="background-color:<?php echo $allEvent->status == 1 ? Params::EVENTS_TITLE_COLORS[$allEvent->title][1] : '#868e96'; ?>; color: white; cursor: pointer;">
                        <?php echo Translate::t(strtolower($allEvent->title), ['ucfirst']) . ' - ' . $employeeName; ?>
                    </span>
                </td>
                <td class="text-nowrap" style="margin: 1px; padding-left: 1px; padding-right: 0px;">
                    <?php
                    $collapseData = $allEvent->status == 2 ? 'data-target=#collapseExample' . $allEvent->id . ' aria-controls=collapseExample' . $allEvent->id : '';
                    ?>
                    <span class="badge" data-toggle="collapse" <?php echo $collapseData; ?> aria-expanded="false" style="background-color:#868e96; color: white; cursor: pointer;">
                        <?php
                        $all_days = explode(',', $allEvent->days);
                        if (count($all_days) > 1) {
                            echo current($all_days) . '-' . end($all_days);
                        } else {
                            echo $all_days[0];
                        }
                        ?>
                    </span>
                </td>
                <td class="text-nowrap" style="margin: auto; <?php echo $allEvent->status == 1 ? '' : 'padding-left: 0px; padding-right: 0px;'; ?>">
                    <?php if($allEvent->status == 1) { ?>
                        <span><?php echo '<i class="fa fa-check" style="color: rgb(66, 146, 68);"></i>'; ?></span>
                    <?php } else { ?>
                        <span class="badge" style="background-color:#868e96;"><?php echo Translate::t(Params::EVENTS_STATUS[$allEvent->status], ['ucfirst']); ?></span>
                    <?php } ?>
                    <div class="collapse" id="collapseExample<?php echo $allEvent->id; ?>">
                        <div class="btn-group btn-group-sm mt-3" role="group" aria-label="Basic example">
                            <a type="" class="btn-sm btn-primary p-1 eventAction" style="cursor: pointer;"  data-accepted="1" data-employee="<?php echo $allEvent->user_id; ?>" data-eventid="<?php echo $allEvent->id; ?>" data-title="<?php echo $allEvent->title; ?>" data-month="<?php echo $allEvent->month; ?>" data-year="<?php echo $allEvent->year; ?>"><i class="fa fa-check"></i></small></a>
                            <a type="" class="btn-sm btn-danger p-1 ml-2 eventAction" style="cursor: pointer;" id="declined" data-accepted="3" data-employee="<?php echo $allEvent->user_id; ?>" data-eventid="<?php echo $allEvent->id; ?>" data-title="<?php echo $allEvent->title; ?>"><small><i class="icon-close"></i></small></a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php }
    } ?>
    </tbody>
</table>
