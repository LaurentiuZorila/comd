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
        'session_department'    => 'department',
        'token_name'            => 'token',
        'lang_id'               => 'langId'
    ]
];