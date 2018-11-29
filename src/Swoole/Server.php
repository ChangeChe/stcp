<?php
/**
 * Created by PhpStorm.
 * User: Change
 * Date: 2018/11/28
 * Time: 19:51
 */

namespace SwooleC\TcpS\Swoole;


use SwooleC\TcpS\Socket\TcpInterface;
use SwooleC\TcpS\Swoole\Traits\LogTrait;
use SwooleC\TcpS\Swoole\Traits\ProcessTitleTrait;
class Server
{
    use LogTrait;
    use ProcessTitleTrait;

    protected $conf;

    /**
     * @var \swoole_server
     */
    protected $swoole;


    public function __construct(array $conf)
    {
        $this->conf = $conf;

        $ip = isset($conf['listen_ip']) ? $conf['listen_ip'] : '127.0.0.1';
        $port = isset($conf['listen_port']) ? $conf['listen_port'] : 5200;
        $socketType = isset($conf['socket_type']) ? (int)$conf['socket_type'] : \SWOOLE_SOCK_TCP;

        if ($socketType === \SWOOLE_SOCK_UNIX_STREAM) {
            $socketDir = dirname($ip);
            if (!file_exists($socketDir)) {
                mkdir($socketDir);
            }
        }

        $settings = isset($conf['swoole']) ? $conf['swoole'] : [];
        $settings['enable_static_handler'] = !empty($conf['handle_static']);

        $serverClass = \swoole_server::class;
        $this->swoole = new $serverClass($ip, $port, \SWOOLE_PROCESS, $socketType);

        $this->swoole->set($settings);

        $this->bindBaseEvent();
        $this->bindSwooleTables();
    }

    protected function bindBaseEvent()
    {
        $eventHandler = function ($method, array $params) {
            try {
                call_user_func_array([$this->getSocketHandler(), $method], $params);
            } catch (\Exception $e) {
                $this->logException($e);
            }
        };

        $this->swoole->on('connect', function () use ($eventHandler) {
            $eventHandler('onConnect', func_get_args());
        });

        $this->swoole->on('receive', function () use ($eventHandler) {
            $eventHandler('onReceive', func_get_args());
        });

        $this->swoole->on('close', function (\swoole_websocket_server $server, $fd, $reactorId) use ($eventHandler) {
            $eventHandler('onClose', func_get_args());
        });
    }
    protected function getSocketHandler()
    {
        static $handler = null;
        if ($handler !== null) {
            return $handler;
        }

        $handlerClass = $this->conf['socket']['handler'];
        $t = new $handlerClass();
        if (!($t instanceof TcpInterface)) {
            throw new \Exception(sprintf('%s must implement the interface %s', get_class($t), TcpInterface::class));
        }
        $handler = $t;
        return $handler;
    }




    protected function bindSwooleTables()
    {
        $tables = isset($this->conf['swoole_tables']) ? (array)$this->conf['swoole_tables'] : [];
        foreach ($tables as $name => $table) {
            $t = new \swoole_table($table['size']);
            foreach ($table['column'] as $column) {
                if (isset($column['size'])) {
                    $t->column($column['name'], $column['type'], $column['size']);
                } else {
                    $t->column($column['name'], $column['type']);
                }
            }
            $t->create();
            $name .= 'Table'; // Avoid naming conflicts
            $this->swoole->$name = $t;
        }
    }

    public function run()
    {
        $this->swoole->start();
    }
}