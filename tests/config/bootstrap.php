<?php

/**
 * @author Junior Grossi <juniorgro@gmail.com>
 */
require __DIR__.'/../../vendor/autoload.php';

$capsule = \Corcel\Database::connect($params = [
    'database' => 'corcel_acf',
    'username' => 'root',
    'password' => '',
    'host' => '127.0.0.1',
]);

// Create a copy of the default connection called alternate:
$config = $capsule->getContainer()->make('config')->get('database.connections')['default'];
$capsule->addConnection($config, 'alternate');
