<?php
/**
 * Created by PhpStorm.
 * User: onetwist
 * Date: 2018-12-19
 * Time: 11:35
 */
include './../core/calendar-init.php';
$frontUser    = new FrontendUser();
$frontRecords = new FrontendProfile();


$eventMonth  = Input::post('event_month');
$eventStatus = Input::post('event_status');

if (!empty($eventMonth) && !empty($eventStatus)) {
    if ($eventStatus != 'all') {
        $where = AC::where([
            ['user_id', $frontUser->userId()],
            ['status', $eventStatus],
            ['month', $eventMonth],
        ]);
    } elseif ($eventStatus == 'all') {
        $where = AC::where([
            ['user_id', $frontUser->userId()],
            ['month', $eventMonth],
        ]);
    } else {
        $where = AC::where([
            ['user_id', $frontUser->userId()],
            ['start_month', $eventMonth],
        ]);
    }
} else {
    if ($eventStatus !== 'all') {
        $where = AC::where([
            ['user_id', $frontUser->userId()],
            ['status', $eventStatus],
        ]);
    } elseif ($eventStatus == 'all') {
        $where = AC::where([
            'user_id', $frontUser->userId(),
        ]);
    }
}
$allEvents = $frontRecords->records(Params::TBL_EVENTS, $where, ['*'], true);
?>

<table class="table">
    <thead>
    <tr role="row">
        <th class="text-white"><?php echo Translate::t('Request', ['ucfirst'=>true]); ?></th>
        <th class="text-white"><?php echo Translate::t('Date', ['ucfirst'=>true]); ?></th>
        <th class="text-white"><?php echo Translate::t('Status', ['ucfirst'=>true]); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (count($allEvents) > 0) {
        foreach ($allEvents as $allEvent) { ?>
            <tr>
                <td>
                    <?php
                    $collapseData = $allEvent->status == 2 ? 'data-target=#collapseExample' . $allEvent->id . ' aria-controls=collapseExample' . $allEvent->id : '';
                    ?>
                    <span class="badge" data-toggle="collapse" <?php echo $collapseData; ?> aria-expanded="false" style="background-color:<?php echo $allEvent->status == 1 ? Params::EVENTS_TITLE_COLORS[$allEvent->title][1] : '#868e96'; ?>; color: white; cursor: pointer;">
                        <?php echo Translate::t(strtolower($allEvent->title), ['ucfirst'=>true]); ?>
                    </span>
                </td>
                <td class="">
                    <?php
                    $collapseData = $allEvent->status == 2 ? 'data-target=#collapseExample' . $allEvent->id . ' aria-controls=collapseExample' . $allEvent->id : '';
                    ?>
                    <span class="badge" data-toggle="collapse" <?php echo $collapseData; ?> aria-expanded="false" style="background-color:#868e96; color: white; cursor: pointer;">
                        <?php
                        $all_days = explode(',', $allEvent->days);
                        if (count($all_days) > 1) {
                            echo current($all_days) . ' - ' . end($all_days);
                        } else {
                            echo $all_days[0];
                        }
                        ?>
                    </span>
                </td>
                <td>
                    <span class="badge" style="background-color: <?php echo $allEvent->status == 1 ? Params::EVENTS_TITLE_COLORS[$allEvent->title][1] : '#868e96'; ?>"><?php echo Params::EVENTS_STATUS[$allEvent->status]; ?></span>
                </td>
            </tr>
        <?php }
    } ?>
    </tbody>
</table>
