<?php

require __DIR__ . '/../vendor/autoload.php';

$container = new Pimple\Container();
$container['db_config'] = function ($c) {
    return require __DIR__ . '/../config/db.php';
};
$container['cache_config'] = function ($c) {
    return require __DIR__ . '/../config/cache.php';
};
$container['requests'] = function ($c) {
    return require __DIR__ . '/../config/requests.php';
};
$container -> register(new VladDnepr\SimpleMMO\Provider\Request());
$container -> register(new VladDnepr\SimpleMMO\Provider\Model());
$container['serializer'] = function ($c) {
    return new \VladDnepr\SimpleMMO\Response\Type\JSON();
};
$container['db'] = function ($c) {
    try {
        return new \PDO(
            $c['db_config']['dsn'],
            $c['db_config']['user'],
            $c['db_config']['password'],
            $c['db_config']['options']
        );
    } catch (\PDOException $e) {
        throw new \VladDnepr\SimpleMMO\BadRequestException('Database error: ' . $e->getMessage());
    }
};
$container['cache'] = function ($c) {
    $cache = new Memcached();
    $cache -> addServer($c['cache_config']['server'], $c['cache_config']['port']);

    $cache -> set('test', TRUE);
    if (!$cache -> get('test')) {
        throw new \VladDnepr\SimpleMMO\BadRequestException('Connect to cache server failed');
    }

    return $cache;
};

$container['template'] = function ($c) {
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
    return new Twig_Environment($loader, array(
//        'cache' => __DIR__ . '/../cache/twig',
    ));
};

$container['server'] = function ($c) {
    return new \VladDnepr\SimpleMMO\Server($c);
};