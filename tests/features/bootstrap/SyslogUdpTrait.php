<?php
/**
 * Created by PhpStorm.
 * User: jaroslawgabara
 * Date: 28/09/16
 * Time: 14:54
 */

namespace features\Cmp\Logging;


trait SyslogUdpTrait
{
    protected function listen($ip, $port)
    {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_bind($socket, $ip, $port);
        $a = socket_listen($socket);
        var_dump($a);
        die;
    }
}