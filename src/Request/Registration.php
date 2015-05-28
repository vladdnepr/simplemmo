<?php 
namespace VladDnepr\SimpleMMO\Request;
 
class Registration extends AbstractRequest {
    protected $methods_available = array('POST');

    function handleData($data)
    {
        return $data;
    }


}