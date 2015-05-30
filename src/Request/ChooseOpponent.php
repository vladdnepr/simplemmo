<?php 
namespace VladDnepr\SimpleMMO\Request;
 
class ChooseOpponent extends API {
    protected $methods_available = array('POST');

    function handleData($data)
    {
        $result = array();

        /* @var \VladDnepr\SimpleMMO\Repository\Combat $combat_repository */
        $combat_repository = $this -> container['combat_repository'];
        /* @var \VladDnepr\SimpleMMO\Repository\Character $char_repository */
        $char_repository = $this -> container['character_repository'];

        if ($combat = $combat_repository -> getPlayerCombat($this -> character)) {
            $combat_repository -> clear($combat);
        }

        foreach($char_repository -> getOpponents($this -> character) as $opponent) {
            $result[] = array(
                'id' => $opponent -> getID(),
                'name' => $opponent -> getName(),
            );
        }


        return $result;
    }
}