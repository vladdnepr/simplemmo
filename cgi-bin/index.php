<?php

require __DIR__ . '/../vendor/autoload.php';
Twig_Autoloader::register();


$container = new Pimple\Container();
$container['requests'] = function ($c) {
    return require __DIR__ . '/../config/requests.php';
};
$container -> register(new VladDnepr\SimpleMMO\Provider\Request());
$container -> register(new VladDnepr\SimpleMMO\Provider\Model());
$container['serializer'] = function ($c) {
    return new \VladDnepr\SimpleMMO\Response\Type\JSON();
};
$container['server'] = function ($c) {
    return new \VladDnepr\SimpleMMO\Server($c);
};

$container['server'] -> handle($_SERVER["REQUEST_URI"]);