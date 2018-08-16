#!/usr/bin/env php
<?php
use DenDude\SocketClient;

require_once __DIR__ . '/vendor/autoload.php';

$params = getopt('', ['host::', 'port::', 'message::']);

$host = $params['address'] ?? '127.0.0.1';
$port = $params['port'] ?? 5030;
$msg = $params['message'] ?? 'Hello';

try {

    $client = new SocketClient($host, $port);
    $client->sendMessage($msg);

    echo $client->getResult() . PHP_EOL;

} catch (Throwable $e) {

    echo 'Exception: ' . $e->getMessage() . PHP_EOL;

} finally {

    unset($client);
}