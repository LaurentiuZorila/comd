<?php

return [
    'mysql' => [
        'host'      => 'laur-mysql',
        'username'  => 'laur',
        'password'  =>  'laur',
        'db'   => 'laur'
    ],

    'remember' => [
        'cookie_name'   => 'hash',
        'cookie_expiry' => 604800
    ],

    'frontSession' => [
        'session_name'          => 'FrontendUser',
        'session_username'      => 'username',
        'session_id'            => 'id',
        'session_office'        => 'office_id',
        'session_department'    => 'department_id',
        'session_user'          => 'user_id',
        'session_supervisor'    => 'supervisor_id',
        'token_name'            => 'token'
    ]
];