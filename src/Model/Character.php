<?php 
namespace VladDnepr\SimpleMMO\Model;
 
class Character extends AbstractModel {

    const LEVELUP_WINS_DIFF = 10;

    protected $id;
    protected $login;
    protected $name;
    protected $level;
    protected $fights;
    protected $wins;
    protected $coins;
    protected $skills;

    protected $fields = array(
        'id',
        'login',
        'name',
        'level',
        'fights',
        'wins',
        'coins',
        'skills',
    );

    public function isExist()
    {
        return (bool)$this -> id;
    }

    public function isBot()
    {
        return strpos($this -> login, 'bot_') !== FALSE;
    }

    public function checkFirstMove()
    {
        return rand(0, 100) >= 50;
    }

    /**
     * @param mixed $coins
     */
    public function setCoins($coins)
    {
        $this->coins = $coins;
    }

    /**
     * @return mixed
     */
    public function getCoins()
    {
        return $this->coins;
    }

    public function addCoins($coins)
    {
        $this -> coins += $coins;
    }

    /**
     * @param mixed $fights
     */
    public function setFights($fights)
    {
        $this->fights = $fights;
    }

    /**
     * @return mixed
     */
    public function getFights()
    {
        return $this->fights;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $wins
     */
    public function setWins($wins)
    {
        $this->wins = $wins;
    }

    /**
     * @return mixed
     */
    public function getWins()
    {
        return $this->wins;
    }

    public function incrementWins()
    {
        $this->wins++;

        if ($this -> wins % self::LEVELUP_WINS_DIFF == 0) {
            $this->level++;
        }

        return $this->wins;
    }

    /**
     * @param mixed $skills
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;
    }

    /**
     * @return mixed
     */
    public function getSkills()
    {
        return $this->skills;
    }

    public function getSkillById($skill_id)
    {
        return isset($this -> skills[$skill_id]) ? $this -> skills[$skill_id] : NULL;
    }



}