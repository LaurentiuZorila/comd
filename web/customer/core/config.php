<?php

return [
    'mysql' => array(
        'host'      => 'laur-mysql',
        'username'  => 'laur',
        'password'  =>  'laur',
        'db'        => 'laur'
    ),
    'remember' => array(
        'cookie_name'   => 'hash',
        'cookie_expiry' => 604800
    ),

    'session' => array(
        'session_name'    => 'user',
        'session_user'    => 'user_id',
        'token_name'      => 'token'
    )
];