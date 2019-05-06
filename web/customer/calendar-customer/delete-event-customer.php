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

// Get data which is deleted
$data = $customerDb->get(Params::TBL_NOTIFICATION, AC::where(['user_id', $userId]), ['*'], false)->first();

// Delete event in TBL Events
$customerDb->delete(Params::TBL_EVENTS, AC::where(['id', $id]));

// Delete notification in TBL Notification
$customerDb->delete(Params::TBL_NOTIFICATION, AC::where(['event_id', $id]));

// Insert delete notification
$customerDb->insert(Params::TBL_NOTIFICATION,
    [
        'user_id'   => $data->user_id,
        'status'    => 4,
        'common'    => 1,
        'response'  => 'event_deleted',
        'title'     => $data->title,
        'days'      => $data->days,
        'response_status'   => 1,
        'date'      => date('Y-m-d H:i:s')
    ]);

    // Cond common table
    $where = AC::where([
        ['employees_id', $userId],
        ['year', $year],
        ['month', $month]
    ]);
    // Cond event table
    $where1 = AC::where([
        ['user_id', $userId],
        ['title', $title],
        ['status', 1],
        ['month', $month],
        ['year', $year]
    ]);

    // Count rows in event table
    $count = $customerDb->get(Params::TBL_EVENTS, $where1)->count();
    // Get record id common table
    $records  = $customerDb->get($table, $where, ['id'])->first();

    if ($count > 0) {
        // All days from event table
        $allDaysEvent = $customerData->records(Params::TBL_EVENTS, $where1, ['days']);
        foreach ($allDaysEvent as $allDays) {
            $eventDays[] = $allDays->days;
        }

        // Sum all days from events table
        $sumDaysEvent = $customerDb->sum(Params::TBL_EVENTS, $where1, 'days_number')->first()->sum;
        $days = implode(',', $eventDays);

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