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

    'frontSession' => [
        'session_name'          => 'name',
        'session_fname'         => 'fname',
        'session_lname'         => 'lname',
        'session_username'      => 'username',
        'session_id'            => 'id',
        'session_office'        => 'office_id',
        'session_department'    => 'department_id',
        'session_user'          => 'user_id',
        'session_supervisor'    => 'supervisor_id',
        'token_name'            => 'token',
        'session_cityId'        => 'cityId',
    ],
    'route' => [
        'calendar'  => 'calendar.php',
        'home'      => 'index.php',
        'lang'      => 'language.php',
        'login'     => 'login.php',
        'logout'    => 'logout.php',
        'messages'  => 'messages.php',
        'updateProfile' => 'updateprofile.php',
        'staffFeedback' => 'stafffeedback.php',
        'feedback'  => 'feedback.php'
    ],
    'token'     => [
        'token'                 => 'token',
        'token_hash'            => 'token_hash',
        'submit_token'          => 'submitToken',
        'filer_submit_token'    => 'filterSubmitToken',
        'employee_deleted'      => 'employeeDeleted',
    ],
];