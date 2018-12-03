<?php
/**
 * Created by PhpStorm.
 * User: Change
 * Date: 2018/12/3
 * Time: 12:12
 */

return [
    'device.data_post'=>'App\TCP\Modules\Device\Service@post',
    'tcp.connect'=>'SwooleC\TcpS\TCP\DefaultService@onConnect',
    'tcp.close'=>'SwooleC\TcpS\TCP\DefaultService@onClose',
];