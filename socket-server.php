#!/usr/bin/env php
<?php
use DenDude\SocketServer;

require_once __DIR__ . '/vendor/autoload.php';

$params = getopt('', ['host::', 'port::', 'threads::']);

$host = $params['host'] ?? '127.0.0.1';
$port = $params['port'] ?? 5030;

$threads = $params['threads'] ?? 1;

$server = new SocketServer($host, $port);

for ($i = 0; $i < $threads; $i ++) {

    $pid_fork = pcntl_fork();

    if ($pid_fork == 0) {
        // child process
        echo 'thread start: ' . $i . PHP_EOL;
        $server->listen($i);
    }
}

while (($cid = pcntl_waitpid(0 , $status)) != -1) {
    $exit_code = pcntl_wexitstatus($status);
    echo '[' . $cid . '] exited with status: ' . $exit_code . PHP_EOL;
}
