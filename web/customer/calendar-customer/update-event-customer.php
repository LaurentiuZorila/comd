<?php
include './../core/init-calendar.php';

$eventId     = Input::get('eventId');
$employeeId  = Input::get('employeeId');
$eventStatus = Input::get('statusEvent');
$table       = Input::get('title');
$month       = Input::get('month');
$year        = Input::get('year');
$title       = ucfirst(Input::get('title'));

switch ($eventStatus) {
    case '1':
        $notificationResponse = 'event_response_success';
        break;
    case '3':
        $notificationResponse = 'event_response_denied';
        break;
}

if ($eventStatus == 3) {
    // Delete event in TBL Events
    try {
        $customerDb->getPdo()->beginTransaction();
        // Delete event
        $delete = $customerDb->delete(Params::TBL_EVENTS, AC::where(['id', $eventId]));
        // Update table notification
        $notificationEvent = $customerDb->update(Params::TBL_NOTIFICATION, [
            'status'    => $eventStatus,
            'response'  => $notificationResponse,
            'response_status'   => true,
            'date'      => date("Y-m-d H:i:s")
        ], [
            'event_id'  => $eventId
        ]);
        $customerDb->getPdo()->commit();
        echo 1;
        exit;
    } catch (Exception $e) {
        $customerDb->getPdo()->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
// Update tbl events
    try {
        $customerDb->getPdo()->beginTransaction();
        $update = $customerDb->update(Params::TBL_EVENTS, [
            'event_status'  => Params::EVENTS_STATUS[$eventStatus],
            'status'        => $eventStatus,
            'updated'       => date("Y-m-d H:i:s")
        ], [
            'id'    => $eventId
        ]);

        // Update table notification
        $notificationEvent = $customerDb->update(Params::TBL_NOTIFICATION, [
            'status'    => $eventStatus,
            'response'  => $notificationResponse,
            'response_status'   => true,
            'date'      => date("Y-m-d H:i:s")
        ], [
            'event_id'  => $eventId
        ]);

        $customerDb->getPdo()->commit();

    } catch (Exception $e) {
        $customerDb->getPdo()->rollBack();
        echo "Error: " . $e->getMessage();
    }

    if ($update) {
        $eventData = $customerData->records(Params::TBL_EVENTS, AC::where(['id', $eventId]), ['*'], false);

        /** Table where need to make changes */
        $table = Params::PREFIX . strtolower($table);
        $where = AC::where([
            ['employees_id', $employeeId],
            ['year', $year],
            ['month', $month]
        ]);

// Check if exist records in common table if don't exist add new row
        $countRecords = $customerDb->get($table, $where)->count();

        if ($countRecords > 0) {
            $where1 = AC::where([
                ['user_id', $employeeId],
                ['title', $title],
                ['year', $year],
                ['month', $month],
                ['status', 1]
            ]);
            // All days from event table
            $allDaysEvent = $customerDb->get(Params::TBL_EVENTS, $where1, ['days'])->results();
            foreach ($allDaysEvent as $allDays) {
                $eventDays[] = $allDays->days;
            }
            // Sum all days from events table
            $sumDaysEvent = $customerDb->sum(Params::TBL_EVENTS, $where1, 'days_number')->first()->sum;
            $days = implode(',', $eventDays);
            $daysNumber = $sumDaysEvent;
            // Get record id common table
            $records  = $customerDb->get($table, $where, ['id'])->first();
            // Update common table
            $data = $customerDb->update($table,
                [
                    'quantity' => $daysNumber,
                    'days'     => $days

                ], [
                    'id' => $records->id
                ]);

        } else {
            // If not result records add new row with data
            $data = $customerDb->insert($table, [
                'offices_id'            => $customerUser->officesId(),
                'departments_id'        => $customerUser->departmentId(),
                'employees_id'          => $employeeId,
                'employees_average_id'  => $employeeId . '_' . $eventData->year,
                'event_id'              => $eventId,
                'year'                  => $eventData->year,
                'month'                 => $eventData->month,
                'quantity'              => $eventData->days_number,
                'days'                  => $eventData->days,
            ]);
        }
    }


    if ($data) {
        echo 1;
        exit;
    } else {
        echo 0;
        exit;
    }
}

