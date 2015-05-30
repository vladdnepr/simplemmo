<?php 
namespace VladDnepr\SimpleMMO\Request;
 
class Attack extends AbstractRequest {
    protected $methods_available = array('POST');
    protected $data_required = array('skill_id');

    function handleData($data)
    {
        $result = array(
            'your_move' => false,
            'opponent_move' => false,
        );

        /* @var \VladDnepr\SimpleMMO\Repository\Combat $combat_repository */
        $combat_repository = $this -> container['combat_repository'];

        $combat = $combat_repository -> getPlayerCombat($this -> character);

        if ($combat) {
            if (!$combat -> isEnded()) {
                if (in_array($data['skill_id'], $combat -> getPlayer1AvailableSkills())) {
                    $combat -> attackPlayer1ToPlayer2($data['skill_id']);

                    $result['your_move'] = $combat -> getLastMove();

                    if (!$combat -> isEnded()) {
                        $combat -> attackBotToPlayer1();

                        $result['opponent_move'] = $combat -> getLastMove();
                    }

                    $combat_repository -> persist($combat);

                    $result += $combat -> getCombatInfo();
                } else {
                    $result = array('error' => "Skill not available");
                }
            } else {
                $result = array('error' => "Combat is ended");
            }

        } else {
            $result = array('error' => "Combat not found");
        }


        return $result;
    }
}