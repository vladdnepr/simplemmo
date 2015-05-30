<?php 
namespace VladDnepr\SimpleMMO\Request;
 
class ClaimPrize extends AbstractRequest {
    protected $methods_available = array('POST');

    function handleData($data)
    {
        $result = array();

        /* @var \VladDnepr\SimpleMMO\Repository\Character $char_repository */
        $char_repository = $this -> container['character_repository'];
        /* @var \VladDnepr\SimpleMMO\Repository\Combat $combat_repository */
        $combat_repository = $this -> container['combat_repository'];

        $combat = $combat_repository -> getPlayerCombat($this -> character);

        if ($combat) {
            if ($combat -> isEnded()) {

                $winner = $combat -> getWinner();

                if ($winner == $this -> character) {
                    $coins = $winner -> getCoins();
                    $coins += $combat -> getWinCoins();
                    $winner -> setCoins($coins);
                    $char_repository -> persist($winner);
                }

                $combat_repository -> clear($combat);

                $result[0] = $winner -> dump();
                $result[1] = $this -> character -> dump();

            } else {
                $result = array('error' => "Combat is not over");
            }

        } else {
            $result = array('error' => "Ended combat not found");
        }


        return $result;
    }
}