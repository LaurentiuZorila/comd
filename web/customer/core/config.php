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
        'session_name'          => 'FrontendUser',
        'session_id'            => 'customer_id',
        'session_department_id' => 'department_id',
        'session_office_id'     => 'office_id',
        'session_supervisor_id' => 'supervisor_id',
        'session_fname'         => 'first_name',
        'session_lname'         => 'last_name',
        'token'                 => '',
        'token_hash'            => '',
        'token_id'              => 'token_id',
        'login_token'           => 'login_token'
    ],
    'token'     => [
        'token'                 => 'token',
        'token_hash'            => 'token_hash',
        'submit_token'          => 'submitToken',
        'filer_submit_token'    => 'filterSubmitToken'
    ]
];