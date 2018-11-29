<?php
/**
 * Created by PhpStorm.
 * User: Change
 * Date: 2018/11/28
 * Time: 19:50
 */

namespace SwooleC\TcpS\Illuminate;


use Illuminate\Support\ServiceProvider;

class TcpSServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/swoole-tcp.php' => base_path('config/swoole-tcp.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/swoole-tcp.php', 'swoole-tcp'
        );

        $this->commands(TcpSCommand::class);
    }
}