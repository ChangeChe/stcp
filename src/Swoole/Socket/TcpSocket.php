<?php

namespace SwooleC\TcpS\Swoole\Socket;

abstract class TcpSocket implements TcpInterface
{

    public function onConnect(\swoole_server $server, $fd, $reactorId)
    {

    }

    public function onClose(\swoole_server $server, $fd, $reactorId)
    {

    }

    public function onBufferFull(\swoole_server $server, $fd)
    {

    }

    public function onBufferEmpty(\swoole_server $server, $fd)
    {

    }

    abstract public function onReceive(\swoole_server $server, $fd, $reactorId, $data);
}