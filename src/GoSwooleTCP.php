<?php
/**
 * Created by PhpStorm.
 * User: Change
 * Date: 2018/11/29
 * Time: 10:26
 */
use SwooleC\TcpS\SwooleTCP;

$input = file_get_contents('php://stdin');
$cfg = json_decode($input, true);

spl_autoload_register(function ($class) {
    $file = __DIR__ . '/' . substr(str_replace('\\', '/', $class), 13) . '.php';
    if (is_readable($file)) {
        require $file;
        return true;
    }
    return false;
});

(new SwooleTCP($cfg['svrConf'], $cfg['laravelConf']))->run();