<?php
/**
 * Created by PhpStorm.
 * User: Change
 * Date: 2018/12/3
 * Time: 12:15
 */

namespace SwooleC\TcpS\Swoole\Traits;

use SwooleC\TcpS\Illuminate\IoCService;
trait ActionTrait
{
    /**
     * 行为映射
     * @var array
     */
    private $mapping = [];

    /**
     * 单例
     * @var array
     */
    private $services = [];

    /**
     * 解析用户的行为
     * @param $action
     */
    public function parseAction($action)
    {
        if(isset($this->mapping[$action])) {
            $service_method = $this->mapping[$action];
            list($service, $method) = explode('@', $service_method);
            if(isset($this->services[$service])) $instance = $this->services[$service];
            else {
                $instance = IoCService::getInstance($service);
                $this->services[$service] = $instance;
            }
            return [$instance, $method];
        }
        new \Exception(sprintf('%s is not defined', $action));
    }

    /**
     * 执行用户行为
     * @param $service
     * @param $method
     * @param $params
     */
    public function doAction($service, $methodName, $params) {
        return $service->{$methodName}($params);
    }
}