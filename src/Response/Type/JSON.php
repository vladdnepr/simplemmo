<?php 
namespace VladDnepr\SimpleMMO\Response\Type;

use \fkooman\Json\Json as Parser;
 
class JSON {

    public function decode($string)
    {
        return Parser::decode($string);
    }

    public function encode($data)
    {
        return Parser::encode($data);
    }

    public function getContentType()
    {
        return 'application/json';
    }
}