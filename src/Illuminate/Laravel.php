<?php
/**
 * Created by PhpStorm.
 * User: Change
 * Date: 2018/11/29
 * Time: 20:28
 */

namespace SwooleC\TcpS\Illuminate;


class Laravel
{
    protected $app;

    /**
     * @var HttpKernel $laravelKernel
     */
    protected $laravelKernel;

    protected static $snapshotKeys = ['config', 'cookie', 'auth', /*'auth.password'*/];

    /**
     * @var array $snapshots
     */
    protected $snapshots = [];

    protected $conf = [];

    protected static $staticBlackList = [
        '/index.php'  => 1,
        '/.htaccess'  => 1,
        '/web.config' => 1,
    ];

    private $rawGlobals = [];

    public function __construct(array $conf = [])
    {
        $this->conf = $conf;
    }

    public function prepareLaravel()
    {
        static::autoload($this->conf['root_path']);
        $this->createApp();
    }

    public static function autoload($rootPath)
    {
        $autoload = $rootPath . '/bootstrap/autoload.php';
        if (file_exists($autoload)) {
            require_once $autoload;
        } else {
            require_once $rootPath . '/vendor/autoload.php';
        }
    }

    protected function createApp()
    {
        $this->app = require $this->conf['root_path'] . '/bootstrap/app.php';
    }


}