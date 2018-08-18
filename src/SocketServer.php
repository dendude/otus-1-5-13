<?php
namespace DenDude;

class SocketServer
{
    protected $socket;
    protected $result;

    protected $host;
    protected $port;

    public function __construct($host, $port)
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if (!is_resource($this->socket)) {
            throw new \Exception('Socket created failed: ' . socket_strerror(socket_last_error()));
        }

        $this->host = $host;
        $this->port = $port;

        $this->init();
    }

    public function __destruct()
    {
        $this->close();
    }

    public function listen($threadNo)
    {
        while (true) {
            $pid = posix_getpid();
            $socket = socket_accept($this->socket);
            echo '[' . $pid . '] [' . $threadNo . '] Acceptor connect: ' . $socket . PHP_EOL;
            socket_write($socket, 'Process pid: ' . $pid . PHP_EOL);
            $command = trim(socket_read($socket, 2048));
            echo '[' . $pid . '] [' . $threadNo . '] Retrieve command: ' . $command . PHP_EOL;
            socket_write($socket, '[' . $command . ']' . PHP_EOL);
            socket_close($socket);
        }
    }

    protected function init()
    {
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);

        if (!socket_bind($this->socket, $this->host, $this->port)) {
            throw new \Exception('Socket bind failed: ' . socket_strerror(socket_last_error()));
        }

        if (!socket_listen($this->socket, 1)) {
            throw new \Exception('Socket listen failed: ' . socket_strerror(socket_last_error()));
        }
    }

    protected function close()
    {
        if (is_resource($this->socket)) {
            socket_close($this->socket);
        }
    }
}