<?php

return [
    'mysql' => array(
        'host'          => 'laur-mysql',
        'username'      => 'laur',
        'password'      => 'laur',
        'db'            => 'laur'
    ),

    'remember' => array(
        'cookie_name'   => 'hash',
        'cookie_expiry' => 604800
    ),

    'session' => array(
        'session_name'          => 'backendUser',
        'session_id'            => 'user_id',
        'session_department'    => 'department_id',
        'token_name'            => 'token_name',
        'token_hash'            => 'token_hash',
        'token_id'              => 'token_id'
    ),

    'frontSession'  => [
        'session_name'          => 'name',
        'session_username'      => 'username',
        'session_id'            => 'id',
        'session_department'    => 'departmentId',
        'session_office'        => 'officeId',
        'session_supervisor'    => 'supervisorId',
        'session_lead_id'       => 'leadId'
    ],
    'customerSession'  => [
        'session_name'          => 'name',
        'session_username'      => 'username',
        'session_id'            => 'id',
        'session_department_id' => 'department_id',
        'session_office_id'     => 'office_id',
        'session_supervisor_id' => 'supervisor_id',
    ],
    'backendSession'  => [
        'session_name'          => 'name',
        'session_username'      => 'username',
        'session_id'            => 'id',
        'session_department'    => 'departmentId'
    ]
];