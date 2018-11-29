<?php

namespace SwooleC\TcpS\Socket;

interface PortInterface
{
    public function __construct(\swoole_server_port $port);
}