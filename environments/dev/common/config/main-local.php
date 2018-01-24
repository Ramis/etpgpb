<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;dbname=etpgpb',
            'username' => 'postgres',
            'password' => 'postgres',
            'charset' => 'utf8',
        ],
    ],
];
