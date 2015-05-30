<?php

use VladDnepr\SimpleMMO\Model\Combat;
use VladDnepr\SimpleMMO\Model\Character;

class CombatTest extends PHPUnit_Framework_TestCase
{
    public function testSetPlayerAndGetId()
    {
        $char1 = new Character(array('id' => 1));
        $char2 = new Character(array('id' => 2));
        $combat = new Combat();
        $combat -> setPlayer1($char1);
        $combat -> setPlayer2($char2);
        $this -> assertEquals($char1 -> getId(), $combat -> getP1id());
        $this -> assertEquals($char2 -> getId(), $combat -> getP2id());
    }

    /**
     * @dataProvider dataProviderIsEnded
     */
    public function testIsEnded($round, $score1, $score2, $ended)
    {
        $combat = new Combat();

        $combat -> setRound($round);
        $combat -> setP1score($score1);
        $combat -> setP2score($score2);

        $this -> assertEquals($ended, $combat -> isEnded());
    }

    public function dataProviderIsEnded()
    {
        $data = array();

        for ($round = 1; $round <=3; $round++) {
            for ($score1 = 0; $score1 <=200; $score1+=10) {
                for ($score2 = 0; $score2 <=200; $score2+=10) {
                    $data[] = array(
                        $round,
                        $score1,
                        $score2,
                        $round > 3 || $score1 > 100 || $score2 > 100 ? true : false
                    );
                }
            }
        }

        return $data;
    }

    /**
     * @dataProvider dataProviderCalculateScores
     */
    public function testCalculateScores($skill1_value, $skill2_value, $result)
    {
        $combat = new Combat();

        $this -> assertEquals(
            $result,
            $combat -> getScoreAfterAttack(
                array('value' => $skill1_value),
                array('value' => $skill2_value)
            )
        );
    }

    public function dataProviderCalculateScores()
    {
        return array(
            array(10, 20, 0),
            array(20, 10, 10),
            array(20, 20, 0),
        );
    }
}