<?php

namespace SwooleC\TcpS\Swoole\Traits;

use SwooleC\TcpS\Illuminate\Laravel;

trait LaravelTrait
{
    protected function initLaravel(array $conf, \swoole_server $swoole)
    {
        $laravel = new Laravel($conf);
        $laravel->prepareLaravel();
        $laravel->bindSwoole($swoole);
        return $laravel;
    }
}