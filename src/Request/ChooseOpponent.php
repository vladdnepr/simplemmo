<?php 
namespace VladDnepr\SimpleMMO\Request;
 
class ChooseOpponent extends AbstractRequest {
    protected $methods_available = array('POST');

    function handleData($data)
    {
        $result = array();

        /* @var \VladDnepr\SimpleMMO\Repository\Character $repository */
        $repository = $this -> container['character_repository'];

        foreach($repository -> getOpponents($this -> character) as $opponent) {
            $result[] = array(
                'id' => $opponent -> getID(),
                'name' => $opponent -> getName(),
            );
        }

        return $result;
    }
}