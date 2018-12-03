<?php
/**
 * Created by PhpStorm.
 * User: Change
 * Date: 2018/12/3
 * Time: 14:39
 */

namespace SwooleC\TcpS\TCP;


class DefaultService
{
    public function onConnect()
    {
        return 'Connect Success';
    }

    public function onClose()
    {
        return 'Bye, bye';
    }
}