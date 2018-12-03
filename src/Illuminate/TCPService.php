<?php
/**
 * Created by PhpStorm.
 * User: Change
 * Date: 2018/11/29
 * Time: 10:59
 */

namespace SwooleC\TcpS\Illuminate;


use SwooleC\TcpS\Swoole\Socket\TcpSocket;
use SwooleC\TcpS\Swoole\Traits\ActionTrait;
use Illuminate\Log\Writer;
class TCPService extends TcpSocket
{
    use ActionTrait;

    public function __construct($svrConf)
    {
        if(is_file($svrConf['routes'])) {
            $this->mapping = require $svrConf['routes'];
        } else {
            $this->mapping = require __DIR__.'/../TCP/routes.php';
        }
    }

    public function onConnect(\swoole_server $server, $fd, $reactorId)
    {
        $action = 'tcp.connect';
        $data = [];
        try {
            list($service, $method) = $this->parseAction($action);
            $response = $this->doAction($service, $method, $data);
            if($response) {
                $server->send($fd, $response);
            }
        } catch (\Exception $e) {
            Log::error('方法执行错误');
            Log::error('Line: ' . $e->getLine() . ' File: ' . $e->getFile() . ' Message:' . $e->getMessage());
        }
    }

    public function onClose(\swoole_server $server, $fd, $reactorId)
    {
        $action = 'tcp.close';
        $data = [];
        try {
            list($service, $method) = $this->parseAction($action);
            $response = $this->doAction($service, $method, $data);
            if($response) {
                $server->send($fd, $response);
            }
        } catch (\Exception $e) {
            Log::error('方法执行错误');
            Log::error('Line: ' . $e->getLine() . ' File: ' . $e->getFile() . ' Message:' . $e->getMessage());
        }
    }

    public function onBufferFull(\swoole_server $server, $fd)
    {

    }

    public function onBufferEmpty(\swoole_server $server, $fd)
    {

    }
    public function onReceive(\swoole_server $server, $fd, $reactorId, $data)
    {
        $data = json_decode($data, true);
        $action = $data['action'];
        $data = json_decode($data['data']);
        try {
            list($service, $method) = $this->parseAction($action);
            $response = $this->doAction($service, $method, $data);
            if($response) {
                $server->send($fd, $response);
            }
        } catch (\Exception $e) {
            Log::error('方法执行错误');
            Log::error('Line: ' . $e->getLine() . ' File: ' . $e->getFile() . ' Message:' . $e->getMessage());
        }
    }
}