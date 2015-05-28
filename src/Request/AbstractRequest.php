<?php 
namespace VladDnepr\SimpleMMO\Request;
 
use VladDnepr\SimpleMMO\BadRequestException;

abstract class AbstractRequest {

    protected $container;
    protected $methods_available = array();

    function __construct(\Pimple\Container $container)
    {
        $this -> container = $container;
    }


    public function handle()
    {
        if (!in_array($_SERVER["REQUEST_METHOD"], $this -> methods_available)) {
            throw new BadRequestException('Request method not supported');
        }

        $data = array();

        $post = $this -> container['server'] -> getRawPOST();

        if (!empty($post)) {
            try {
                $data = $this -> container['serializer'] -> decode($post);
            } catch (\InvalidArgumentException $e) {
                throw new BadRequestException("Invalid json");
            }
        }

        return $this -> handleData($data);
    }

    abstract function handleData($data);
}