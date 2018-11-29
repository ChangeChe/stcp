<?php
/**
 * Created by PhpStorm.
 * User: Change
 * Date: 2018/5/30
 * Time: 17:04
 */

namespace SwooleC\TcpS\Illuminate;

use ReflectionClass;
use Log;
class IoCService{
    // 获得类的对象实例
    public static function getInstance($className,$params=[]) {
        $exits = [];
        foreach ($params as $param){
            $exits[get_class($param)] = $param;
        }
        $paramArr = self::getMethodParams($className, '__construct',$exits);
        try{
            return (new ReflectionClass($className))->newInstanceArgs($paramArr);
        }catch (\Exception $e){
            Log::error('Line: ' . $e->getLine() . ' File: ' . $e->getFile() . ' Message:' . $e->getMessage());
            return null;
        }
    }

    /**
     * @param $className
     * @param $methodName
     * @param array $cparams
     * @param array $params
     * @return mixed
     */
    public static function make($className, $methodName, $cparams=[], $params = []) {

        // 获取类的实例
        $instance = self::getInstance($className, $cparams);

        // 获取该方法所需要依赖注入的参数
        $paramArr = self::getMethodParams($className, $methodName);

        return $instance->{$methodName}(...array_merge($paramArr, $params));
    }

    /**
     * @param $className
     * @param string $methodsName
     * @param array $exits
     * @return array
     */
    protected static function getMethodParams($className, $methodsName = '__construct',$exits=[]) {
        $paramArr = []; // 记录参数，和参数类型
        try{
            // 通过反射获得该类
            $class = new ReflectionClass($className);
            // 判断该类是否有构造函数
            if ($class->hasMethod($methodsName)) {
                // 获得构造函数
                $construct = $class->getMethod($methodsName);

                // 判断构造函数是否有参数
                $params = $construct->getParameters();

                if (count($params) > 0) {

                    // 判断参数类型
                    foreach ($params as $key => $param) {

                        if ($paramClass = $param->getClass()) {
                            // 获得参数类型名称
                            $paramClassName = $paramClass->getName();
                            if(array_key_exists($paramClassName,$exits)){
                                $paramArr[] = $exits[$paramClassName];
                            }else{
                                // 获得参数类型
                                $args = self::getMethodParams($paramClassName);
                                $paramArr[] = (new ReflectionClass($paramClass->getName()))->newInstanceArgs($args);
                            }
                        }
                    }
                }
            }
        }catch (\Exception $e){
            Log::error('Line: ' . $e->getLine() . ' File: ' . $e->getFile() . ' Message:' . $e->getMessage());
        }
        return $paramArr;
    }
}