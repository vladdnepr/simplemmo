<?php

use VladDnepr\SimpleMMO\Model\Character;

class CharacterTest extends PHPUnit_Framework_TestCase
{
    public function testAddCoins()
    {
        $char = new Character();
        $char -> setCoins(0);
        $char -> addCoins(50);
        $this -> assertEquals(50, $char -> getCoins());
    }

    public function testIncrementWinsAndLevelUp()
    {
        $char = new Character();
        $char -> setWins(0);
        $char -> setLevel(1);

        for ($i = 0; $i < Character::LEVELUP_WINS_DIFF; $i++) {
            $char -> incrementWins();
        }

        $this -> assertEquals(2, $char -> getLevel());
    }
}