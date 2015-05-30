<?php 
namespace VladDnepr\SimpleMMO\Model;

use VladDnepr\SimpleMMO\Model\Character;

class Combat extends AbstractModel {

    /**
     * @var Character
     */
    protected $player1;

    /**
     * @var Character
     */
    protected $player2;

    protected $round;
    protected $p1id;
    protected $p2id;
    protected $p1scores;
    protected $p2scores;
    protected $p1score;
    protected $p2score;
    protected $p1last_skill;
    protected $p2last_skill;
    protected $history = array();

    protected $fields = array(
        'round',
        'p1id',
        'p2id',
        'p1scores',
        'p2scores',
        'p1score',
        'p2score',
        'p1last_skill',
        'p2last_skill',
        'history',
    );

    /**
     * @return mixed
     */
    public function getP1id()
    {
        return $this->p1id;
    }

    /**
     * @return mixed
     */
    public function getP2id()
    {
        return $this->p2id;
    }

    /**
     * @param mixed $history
     */
    public function setHistory($history)
    {
        $this->history = $history;
    }

    /**
     * @return mixed
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @param Character  $player1
     */
    public function setPlayer1(Character $player1)
    {
        $this->player1 = $player1;
        $this->p1id = $player1->getId();
    }

    /**
     * @return Character
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * @param Character  $player2
     */
    public function setPlayer2(Character $player2)
    {
        $this->player2 = $player2;
        $this->p2id = $player2->getId();
    }

    /**
     * @return Character
     */
    public function getPlayer2()
    {
        return $this->player2;
    }

    /**
     * @param mixed $p1last_skill
     */
    public function setP1lastSkill($p1last_skill)
    {
        $this->p1last_skill = $p1last_skill;
    }

    /**
     * @return mixed
     */
    public function getP1lastSkill()
    {
        return $this->p1last_skill;
    }

    /**
     * @param mixed $p1score
     */
    public function setP1score($p1score)
    {
        $this->p1score = $p1score;
    }

    /**
     * @return mixed
     */
    public function getP1score()
    {
        return $this->p1score;
    }

    /**
     * @param mixed $p1scores
     */
    public function setP1scores($p1scores)
    {
        $this->p1scores = $p1scores;
    }

    /**
     * @return mixed
     */
    public function getP1scores()
    {
        return $this->p1scores;
    }

    /**
     * @param mixed $p2last_skill
     */
    public function setP2lastSkill($p2last_skill)
    {
        $this->p2last_skill = $p2last_skill;
    }

    /**
     * @return mixed
     */
    public function getP2lastSkill()
    {
        return $this->p2last_skill;
    }

    /**
     * @param mixed $p2score
     */
    public function setP2score($p2score)
    {
        $this->p2score = $p2score;
    }

    /**
     * @return mixed
     */
    public function getP2score()
    {
        return $this->p2score;
    }

    /**
     * @param mixed $p2scores
     */
    public function setP2scores($p2scores)
    {
        $this->p2scores = $p2scores;
    }

    /**
     * @return mixed
     */
    public function getP2scores()
    {
        return $this->p2scores;
    }

    /**
     * @param mixed $round
     */
    public function setRound($round)
    {
        $this->round = $round;
    }

    /**
     * @return mixed
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Check combat is started
     * @return bool
     */
    public function isStarted()
    {
        return (bool) $this -> round;
    }

    /**
     * Check combat is ended
     * @return bool
     */
    public function isEnded()
    {
        return $this -> round > 3 || $this -> p1score > 100 || $this -> p2score > 100;
    }

    /**
     * @return Character|null
     */
    public function getWinner()
    {
        $winner = NULL;

        if ($this -> isEnded()) {
            if ($this -> p1score > $this -> p2score) {
                $winner = $this -> player1;
            } elseif ($this -> p2score > $this -> p1score) {
                $winner = $this -> player2;
            } else {
                // Choose winner by random
                $rand = rand(1,100);

                if ($rand >= 50) {
                    $winner = $this -> player1;
                } else {
                    $winner = $this -> player2;
                }
            }
        }

        return $winner;
    }

    /**
     * Get winner coins
     * @return null|int
     */
    public function getWinCoins()
    {
        $coins = NULL;

        if ($this -> isEnded()) {
            $winner = $this -> getWinner();

            if ($this -> player1 == $winner) {
                $coins = $this -> p1score;
            } elseif ($this -> player2== $winner) {
                $coins = $this -> p2score;
            }
        }

        return $coins;
    }

    /**
     * Initialize combat
     * @return bool
     */
    public function start()
    {
        $result = FALSE;

        if (!$this -> round) {
            $this -> round = 1;// Round
            $this -> p1scores = array(); // Scores of each round
            $this -> p2scores = array(); // Scores of each round
            $this -> p1score = 0;// Total score
            $this -> p2score = 0;// Total score
        }

        return $result;
    }


    /**
     * Attack P1 to P2
     * @param $skill_id
     */
    public function attackPlayer1ToPlayer2($skill_id)
    {
        // Get score
        $score = $this -> getScoreAfterAttack(
            $this -> player1 -> getSkillById($skill_id),
            $this -> player2 -> getSkillById($skill_id)
        );

        // Save to history
        $this -> history[] = array(
            'id' => $this -> player1 -> getId(),
            'skill_id' => $skill_id,
            'score' => $score
        );
        // Save last combat score
        $this -> p1scores[$skill_id] = $score;
        // Save summary of scores
        $this -> p1score = array_sum($this -> p1scores);
        // Save last used skill
        $this -> p1last_skill = $skill_id;

        // Change round of combat
        if (count($this -> p1scores) == count($this -> p2scores)) {
            $this -> round = count($this -> p1scores) + 1;
        }
    }

    /**
     * Attack P2 to P1
     * @param $skill_id
     */
    public function attackPlayer2ToPlayer1($skill_id)
    {
        // Get score
        $score = $this -> getScoreAfterAttack(
            $this -> player2 -> getSkillById($skill_id),
            $this -> player1 -> getSkillById($skill_id)
        );

        // Save to history
        $this -> history[] = array(
            'id' => $this -> player2 -> getId(),
            'skill_id' => $skill_id,
            'score' => $score
        );
        // Save last combat score
        $this -> p2scores[$skill_id] = $score;
        // Save summary of scores
        $this -> p2score = array_sum($this -> p2scores);
        // Save last used skill
        $this -> p2last_skill = $skill_id;

        // Change round of combat
        if (count($this -> p1scores) == count($this -> p2scores)) {
            $this -> round = count($this -> p2scores) + 1;
        }
    }

    /**
     * Attack P2 as bot to P1
     */
    public function attackBotToPlayer1()
    {
        $available_skills = $this -> getPlayer2AvailableSkills();
        $this -> attackPlayer2ToPlayer1($available_skills[array_rand($available_skills)]);
    }

    /*
     * Get P1 available skills ids
     */
    public function getPlayer1AvailableSkills()
    {
        return array_values(
            array_diff(
                array_keys($this -> player1 -> getSkills()),
                array($this -> p1last_skill, $this -> p2last_skill)
            )
        );
    }

    /**
     * Get P2 available skills ids
     * @return array
     */
    public function getPlayer2AvailableSkills()
    {
        return array_values(
            array_diff(
                array_keys($this -> player2 -> getSkills()),
                array($this -> p2last_skill, $this -> p1last_skill)
            )
        );
    }

    /**
     * Get last move result
     * @return mixed
     */
    public function getLastMove()
    {
        return end($this -> history);
    }

    /**
     * Get round info
     * @return array
     */
    public function getCombatInfo()
    {
        return array(
            'available_skills' => $this -> getPlayer1AvailableSkills(),

            'your_skills' => $this -> getPlayer1() -> getSkills(),
            'opponent_skills' => $this -> getPlayer2() -> getSkills(),

            'your_score' => $this -> getP1score(),
            'opponent_score' => $this -> getP2score(),

            'history' => $this -> getHistory(),
            'ended' => $this -> isEnded(),
            'winner_id' => $this -> isEnded() ? $this -> getWinner() -> getId() : null
        );
    }

    /**
     * Calculate score after attack
     * @param $skill1
     * @param $skill2
     * @return mixed
     */
    protected function getScoreAfterAttack($skill1, $skill2)
    {
        return max($skill1['value'] - $skill2['value'], 0);
    }

}