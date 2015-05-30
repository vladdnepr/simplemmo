<?php 
namespace VladDnepr\SimpleMMO\Request;
 
class StartCombat extends AbstractRequest {
    protected $methods_available = array('POST');
    protected $data_required = array('opponent');

    function handleData($data)
    {
        $result = array(
            'opponent_move' => false
        );

        /* @var \VladDnepr\SimpleMMO\Repository\Character $char_repository */
        /* @var \VladDnepr\SimpleMMO\Repository\Combat $combat_repository */
        $char_repository = $this -> container['character_repository'];
        $combat_repository = $this -> container['combat_repository'];

        $combat = $combat_repository -> getPlayerCombat($this -> character);
        $opponent = $char_repository -> getByID($data['opponent']);

        if (!$combat) {
            if ($opponent) {
                $combat = $combat_repository -> getNewCombat();
                $combat -> setPlayer1($this -> character);
                $combat -> setPlayer2($opponent);
                $combat -> start();

                if ($opponent -> checkFirstMove()) {
                    $combat -> attackBotToPlayer1();
                    $result['opponent_move'] = $combat -> getLastMove();
                }

                $combat_repository -> persist($combat);

                $result += $combat -> getCombatInfo();
            } else {
                $result = array('error' => "Opponent not found");
            }
        } else {
            if ($combat -> isEnded()) {
                $result = array('error' => "Combat already ended");
            } else {
                $result = array('error' => "Combat already started");
            }
        }


        return $result;
    }


}