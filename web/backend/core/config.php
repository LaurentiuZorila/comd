<?php

return [
    'mysql' => [
        'host'      => 'laur-mysql',
        'username'  => 'laur',
        'password'  => 'laur',
        'db'        => 'laur'
    ],

    'remember' => [
        'cookie_name'   => 'hash',
        'cookie_expiry' => 604800
    ],

    'session' => [
        'session_name'          => 'name',
        'session_id'            => 'id',
        'session_department'    => 'departmentId',
        'session_office'        => 'officeId',
        'token_name'            => 'token',
        'lang_id'               => 'langId',
        'session_fname'         => 'first_name',
        'session_lname'         => 'last_name',
        'session_cityId'        => 'cityId',
    ],
    'route' => [
        'addUser'   => 'addusers.php',
        'calendar'  => 'calendar.php',
        'home'      => 'index.php',
        'lang'      => 'language.php',
        'login'     => 'login.php',
        'logout'    => 'logout.php',
        'messages'  => 'messages.php',
        'allStaff'  => 'allstaff.php',
        'employees' => 'employees.php',
        'emplData'  => 'employeesdata.php',
        'export'    => 'export.php',
        'register'  => 'register.php',
        'staffProfile'  => 'staffprofile.php',
        'updateProfile' => 'updateprofile.php',
        'updateStaffProfile'    => 'updatestaffprofile.php',
        'updateUserProfile'     => 'updateUserProfile.php',
    ],
    'token'     => [
        'token'                 => 'token',
        'token_hash'            => 'token_hash',
        'submit_token'          => 'submitToken',
        'filer_submit_token'    => 'filterSubmitToken',
        'employee_deleted'      => 'employeeDeleted',
    ],
    'notification'  => [
        'employeeName'  => 'notificationEmployeeName'
    ],
     'errors'   => [
         'office_not_configured' => 'officenotconfigured'
     ],
];