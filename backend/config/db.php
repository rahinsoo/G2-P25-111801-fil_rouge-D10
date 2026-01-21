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
// Vérifier qu'il existe.
if (is_file($localDbFile)) {
    // On override db.php par db.local.php
    $config['db'] = array_replace($config['db'], (require $localDbFile)['db'] ?? []);
}

// On retourne la bonne config.
return $config;