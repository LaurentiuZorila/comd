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
    ]
];