<?php 
namespace VladDnepr\SimpleMMO\Request;
 
use VladDnepr\SimpleMMO\Model\Character;

class Login extends API {
    protected $methods_available = array('POST');
    protected $need_character = false;
    protected $data_required = array('login');

    function handleData($data)
    {
        /* @var \VladDnepr\SimpleMMO\Repository\Character $repository */
        $repository = $this -> container['character_repository'];
        /* @var Character $char*/
        $char = $repository -> getByLogin($data['login']);

        if ($char) {
            $result = array(
                'char' => $char -> dump(),
                'token' => $repository -> getCharacterToken($char)
            );
        } else {
            $result = array('error' => "User with login " . $data['login'] . " not found");
        }

        return $result;
    }
}