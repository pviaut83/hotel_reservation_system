<?php
return [
    'roles' => [
        'admin' => [
            'manage_users',
            'manage_reservations',
            'manage_payments'
        ],
        'receptionist' => [
            'manage_reservations'
        ],
        'user' => [
            'create_reservations',
            'view_reservations'
        ],
    ],
];
?>