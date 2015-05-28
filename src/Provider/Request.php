<?php

namespace VladDnepr\SimpleMMO\Provider;

use VladDnepr\SimpleMMO\Server;

class Request implements \Pimple\ServiceProviderInterface
{
    public function register(\Pimple\Container $pimple)
    {
        foreach ($pimple['requests'] as $request_name => $request_class) {
            $pimple[Server::REQUEST_NAME_PREFIX . $request_name] = function ($c) use ($request_class) {
                $request_class = '\\VladDnepr\\SimpleMMO\\Request\\' . $request_class;
                return new $request_class($c);
            };
        }
    }
}