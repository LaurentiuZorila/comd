<?php
require_once './../core/init-calendar.php';

$id     = Input::get('id');
$table  = Input::get('table');
$status = Input::get('status');
$userId = Input::get('userId');
$month  = Input::get('month');
$year   = Input::get('year');
$title  = ucfirst(Input::get('table'));

// Common table
$table  = strtolower($table);
$table  = Params::PREFIX . $table;


// Delete event in TBL Events
$customerDb->delete(Params::TBL_EVENTS, AC::where(['id', $id]));

// Delete notification in TBL Notification
$customerDb->delete(Params::TBL_NOTIFICATION, AC::where(['event_id', $id]));

    $where = AC::where([
        ['employees_id', $userId],
        ['year', $year],
        ['month', $month]
    ]);

    $where1 = AC::where([
        ['user_id', $userId],
        ['title', $title],
        ['status', 1]
    ]);

    // Count rows in event table
    $count = $customerDb->get(Params::TBL_EVENTS, $where1)->count();

    if ($count > 0) {
        // All days from event table
        $allDaysEvent = $customerData->records(Params::TBL_EVENTS, $where1, ['days']);
        foreach ($allDaysEvent as $allDays) {
            $eventDays[] = $allDays->days;
        }

        // Sum all days from events table
        $sumDaysEvent = $customerDb->sum(Params::TBL_EVENTS, $where1, 'days_number')->first()->sum;
        $days = implode(',', $eventDays);

        // Get record id common table
        $records  = $customerDb->get($table, $where, ['id'])->first();

        // Update common table
        $action = $customerDb->update($table,
            [
                'quantity' => $sumDaysEvent,
                'days'     => $days

            ], [
                'id' => $records->id
            ]);

    } else {
        $action = $customerDb->delete($table, AC::where(['id', $records->id]));
    }

if (!$action) {
    echo 0;
} else {
    echo 1;
}
?>