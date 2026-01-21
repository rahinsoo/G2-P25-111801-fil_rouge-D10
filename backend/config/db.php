<?php

$config = [
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'db' => 'data_punch',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ]
];

$localDbFile = __DIR__ . '/db.local.php';

if (is_file($localDbFile)) {
    $config['db'] = array_replace($config['db'], (require $localDbFile)['db'] ?? []);
}

return $config;