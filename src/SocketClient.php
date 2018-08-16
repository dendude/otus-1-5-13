<?php
namespace DenDude;

class SocketClient
{
    protected $socket;
    protected $result;

    public function __construct($host, $port)
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if (!is_resource($this->socket)) {
            throw new \Exception('Socket created failed: ' . socket_strerror(socket_last_error()));
        }

        $connect = socket_connect($this->socket, $host, $port);

        if ($connect === false) {
            $this->close();
            throw new \Exception('Socket connect failed: ' . socket_strerror(socket_last_error()));
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    public function sendMessage($msg)
    {
        socket_write($this->socket, $msg, strlen($msg));

        $this->result = '';

        while (($chunk = socket_read($this->socket, 2048) ) !== '') {
            $this->result .= $chunk;
        }
    }

    public function getResult()
    {
        return $this->result;
    }

    protected function close()
    {
        if (is_resource($this->socket)) {
            socket_close($this->socket);
        }
    }
}