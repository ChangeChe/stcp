<?php

namespace SwooleC\TcpS\Swoole\Socket;

interface PortInterface
{
    public function __construct(\swoole_server_port $port);
}