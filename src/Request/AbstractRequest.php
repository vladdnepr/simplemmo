<?php 
namespace VladDnepr\SimpleMMO\Request;
 
use VladDnepr\SimpleMMO\BadRequestException;
use VladDnepr\SimpleMMO\Model\Character;

abstract class AbstractRequest {

    protected $container;

    function __construct(\Pimple\Container $container)
    {
        $this -> container = $container;
    }
}