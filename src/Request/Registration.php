<?php 
namespace VladDnepr\SimpleMMO\Request;
 
use VladDnepr\SimpleMMO\Model\Character;

class Registration extends AbstractRequest {
    protected $methods_available = array('POST');
    protected $data_required = array('name', 'login');
    protected $need_character = false;

    function handleData($data)
    {
        /* @var \VladDnepr\SimpleMMO\Repository\Character $repository */
        $repository = $this -> container['character_repository'];
        $char = $repository -> getByLogin($data['login']);

        if (!$char) {
            /* @var Character $char*/
            $char = $repository -> getNewCharacter();
            $char -> setLogin($data['login']);
            $char -> setName($data['name']);

            if ($char = $repository -> persist($char)) {

                $result = array('token' => $repository -> getCharacterToken($char));
            } else {
                $result = array('error' => "User registration failed");
            }
        } else {
            $result = array('error' => "User with login " . $data['login'] . " is exist");
        }

        return $result;
    }


}