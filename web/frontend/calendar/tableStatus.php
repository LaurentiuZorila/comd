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
        <th class="text-primary"><?php echo Translate::t('Request', ['ucfirst'=>true]); ?></th>
        <th class="text-primary"><?php echo Translate::t('Date', ['ucfirst'=>true]); ?></th>
        <th class="text-primary"><?php echo Translate::t('Status', ['ucfirst'=>true]); ?></th>
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
                    <a class="" style="cursor: pointer;" type="button" data-toggle="collapse" <?php echo $collapseData; ?> aria-expanded="false">
                        <?php echo Translate::t(strtolower($allEvent->title), ['ucfirst'=>true]); ?>
                    </a>
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
                </td>
            </tr>
        <?php }
    } ?>
    </tbody>
</table>
