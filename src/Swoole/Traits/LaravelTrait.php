<?php

namespace SwooleC\TcpS\Swoole\Traits;

use SwooleC\TcpS\Illuminate\Laravel;

trait LaravelTrait
{
    protected function initLaravel(array $conf)
    {
        $laravel = new Laravel($conf);
        $laravel->prepareLaravel();
        return $laravel;
    }
}