<?php
spl_autoload_register(function($class_name){
    //commonClasses directories
    $directorys = array(
        './../customerClasses/',
        './../../common/classes/',
        './../../customerClasses/',
        './../../../common/classes/'
    );

    //for each directory
    foreach($directorys as $directory)
    {
        //see if the file exsists
        if(file_exists($directory.$class_name . '.php'))
        {
            require_once($directory.$class_name . '.php');
            return;
        }
    }
});

$year   = Input::get('year');
$month  = Input::get('month');
$tables = Input::get('tables');
$response = [];

/** Check if validation is passed */
if (!empty($year) && !empty($month) && !empty($tables)) {
    $update = new UpdateDb();
    $title  = ucfirst($tables);

//        if (count($months) > 0) {
//            foreach ($months as $dbMonth) {
//                $allMonths[] = $dbMonth->month;
//                // Months from database
//                $allMonths = array_unique($allMonths);
//            }
//        } else {
//            $allMonths = [];
//        }

    // First delete present records if update button si checked and then Update form file
    if ($update->toDelete()) {
        // Delete conditions
        $whereCommon = AC::where([['offices_id', $lead->officesId()], ['month',$month], ['year', $year]]);
        $whereEvent  = AC::where([['lead_id', $lead->officesId()], ['month',$month], ['year', $year]]);

        $deleteCommonData       = CustomerDB::getInstance()->delete($update->getTable(), $whereCommon);
        $deleteEventData        = CustomerDB::getInstance()->delete(Params::TBL_EVENTS, $whereEvent);

        if (!$deleteCommonData && !$deleteEventData) {
            $response = ['Response' => 'Db_error'];
//            Errors::setErrorType('danger', Translate::t('Db_error'));
        }
    }

    // Check for valid extension
    if (in_array($update->extension, $update->extensions) && $update->size > 0) {
        // Open the file for reading
        if (($h = fopen("{$update->filename}", "r")) !== FALSE) {
            // Read file
            $data = fgetcsv($h, 1000, ",");
            if ($data[0] === 'Id' || $data[1] === 'Name' || $data[2] === 'Quantity'|| $data[3] === 'Days') {
                while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                    if (is_numeric($data[2]) && Dates::checkDays($data[3])) {
                        $quantity = !empty($data[2]) ? $data[2] : 0;
                        $startDate = Dates::startDate(Dates::makeDateForDb($data[3], $month), $year);
                        $endDate   = Dates::endDate(Dates::makeDateForDb($data[3], $month), $year);

                        // Start inserting into DB
                        try {
                            $leadDb->getPdo()->beginTransaction();
                            // Insert data in common table
                            $lead->insert($update->getTable(), [
                                'offices_id'            => $lead->officesId(),
                                'departments_id'        => $lead->departmentId(),
                                'year'                  => $year,
                                'month'                 => $month,
                                'employees_id'          => $data[0],
                                'employees_average_id'  => $data[0] . '_' . $year,
                                'insert_type'           => Params::INSERT_TYPE['file'],
                                'quantity'              => $quantity,
                                'days'                  => Dates::makeDateForDb($data[3], $month)
                            ]);
                            // Insert data in events table
                            $lead->insert(Params::TBL_EVENTS, [
                                'user_id'   => $data[0],
                                'lead_id'   => $lead->customerId(),
                                'title'     => $title,
                                'Event_status'  => 'Accepted',
                                'start'     => $startDate,
                                'end'       => $endDate,
                                'days_number'   => $quantity,
                                'days'      => Dates::makeDateForDb($data[3], $month),
                                'month'     => $month,
                                'year'      => $year,
                                'status'    => 1,
                                'added'     => date('Y-m-d H:m:s'),
                                'updated'   => date('Y-m-d H:m:s')
                            ]);
                            $lead->insert(Params::TBL_NOTIFICATION, [
                                'user_id'   => $data[0],
                                'lead_id'   => $lead->customerId(),
                                'status'    => 1,
                                'view'      => 1,
                                'employee_view'     => 0,
                                'response'          => 'navNotification',
                                'response_status'   => 1,
                                'date'              => date('Y-m-d H:m:s')
                            ]);
                            $leadDb->getPdo()->commit();
                        } catch (PDOException $e) {
                            $leadDb->getPdo()->rollBack();
                            Errors::setErrorType('danger', $e->getMessage());
                        }
                    } else {
                        $response = ['Response' => 'type_int'];
//                        Errors::setErrorType('danger', Translate::t('type_int', ['ucfirtst'=>true]));
                    }
                }
            } else {
                $response = ['Response' => 'not_correct_file'];
//                Errors::setErrorType('danger', Translate::t('not_correct_file', ['ucfirst'=>true]));
            }

            if ($lead->success() && count($response) === 0) {
                $response = ['Response' => 'Db_success'];
//                Errors::setErrorType('success', Translate::t('Db_success'));
            } else {
                $response = ['Response' => 'Db_error'];
//                Errors::setErrorType('danger', Translate::t('Db_error'));
            }
            // Close the file
            fclose($h);
        }
    } else {
        $response = ['Response' => 'Csv_extension'];
//        Errors::setErrorType('warning', Translate::t('Csv_extension'));
    }
} else {
    echo json_encode($response);
}

