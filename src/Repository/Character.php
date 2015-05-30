<?php 
namespace VladDnepr\SimpleMMO\Repository;

use VladDnepr\SimpleMMO\Model\Character as Model;

class Character {

    const CHAR_CACHE_NAME_PREFIX = 'character_';

    protected $container;

    protected static $cacheById = array();

    function __construct(\Pimple\Container $container)
    {
        $this -> container = $container;
    }

    /**
     * Get randomly opponents with same level
     * @param Model $char
     * @param int $limit
     * @return \VladDnepr\SimpleMMO\Model\Character[]
     */
    public function getOpponents(Model $char, $limit = 2)
    {
        $result = array();

        /* @var \PDOStatement $stmt */
        $stmt = $this -> container['db'] -> prepare('SELECT id FROM characters where level = :level AND login LIKE "bot_%" LIMIT 1000');
        $stmt -> execute(array('level' => $char -> getLevel()));
        $opponents_ids = $stmt -> fetchAll(\PDO::FETCH_COLUMN);
        $opponents_ids_keys = array_rand($opponents_ids, $limit);

        foreach($opponents_ids_keys as $opponent_id_key) {
            $result[] = $this -> getByID($opponents_ids[$opponent_id_key]);
        }

        return $result;
    }

    public function getByID($id)
    {
        $char = $this -> getFromCache($id, 'id');

        if (!$char) {
            $char = $this -> fetchByColumn('SELECT * FROM characters where id = ? LIMIT 1', $id);
        }

        return $char;
    }

    public function getByLogin($login)
    {
        $char = $this -> getFromCache($login, 'login');

        if (!$char) {
            $char = $this -> fetchByColumn('SELECT * FROM characters where login = ? LIMIT 1', $login);
        }

        return $char;
    }

    public function getByToken($token)
    {
        $char = NULL;

        $token = "char_token_" . $token;
        $id = $this -> container['cache'] -> get($token);

        if ($id) {
            $char = $this -> getByID($id);
        }

        return $char;
    }

    /**
     * Generate session token, save it to cache and return
     * @param Model $char
     * @return null|string
     */
    public function getCharacterToken(Model $char)
    {
        $token = NULL;

        if ($char -> isExist()) {
            $token = sha1($char -> getLogin() . microtime(TRUE));
            $this -> container['cache'] -> set("char_token_" . $token, $char -> getId());
        }

        return $token;
    }

    /**
     * Save character
     * @param Model $char
     * @return bool|null|Model
     */
    public function persist(Model $char)
    {
        $result = FALSE;

        $db = $this -> container['db'];

        if ($char -> isExist()) {
            $stmt = $db -> prepare(
                'UPDATE characters
                SET `name` = :name, `login` = :login, level = :level, fights = :fights, wins = :wins, coins = :coins
                where id = :id'
            );
            $result = $stmt -> execute(
                array(
                    'id' => $char -> getId(),
                    'name' => $char -> getName(),
                    'login' => $char -> getLogin(),
                    'level' => $char -> getLevel(),
                    'fights' => $char -> getFights(),
                    'wins' => $char -> getWins(),
                    'coins' => $char -> getCoins(),
                )
            );

            if ($result) {
                $this -> clearFromCache($char);
                $result = $this -> getByID($db -> lastInsertId());
            }
        } else {
            $stmt = $db -> prepare(
                'INSERT INTO characters
                SET `name` = :name, `login` = :login, `level` = :level, `fights` = :fights, `wins` = :wins, `coins` = :coins'
            );
            $is_inserted = $stmt -> execute(
                array(
                    'name' => $char -> getName(),
                    'login' => $char -> getLogin(),
                    'level' => $char -> getLevel() ? $char -> getLevel() : 1,
                    'fights' => $char -> getFights() ? $char -> getFights() : 0,
                    'wins' => $char -> getWins() ? $char -> getWins() : 0,
                    'coins' => $char -> getCoins() ? $char -> getCoins() : 0,
                )
            );

            if ($is_inserted) {
                $char_id = $db -> lastInsertId();
                $this -> fillSkillsForCharacter($char_id);
                $result = $this -> getByID($char_id);
            }
        }

        return $result;
    }

    public function getNewCharacter()
    {
        return new Model();
    }

    protected function fetchByColumn($sql, $value)
    {
        $result = NULL;

        $stmt = $this -> container['db'] -> prepare($sql);
        $stmt -> execute(array($value));

        if ($data = $stmt -> fetch()) {
            $data['skills'] = $this -> getSkillsById($data['id']);
            $result = new Model($data);
            $this -> setToCache($result);
        }

        return $result;
    }

    /**
     * Get char skill values
     * @param $id
     * @return array
     */
    protected function getSkillsById($id)
    {
        $skills = array();

        $stmt = $this -> container['db'] -> prepare(
            "SELECT skills.id, skills.name, characters_skills.value
            FROM `characters`
            RIGHT JOIN characters_skills ON (characters.id = characters_skills.character_id)
            LEFT JOIN skills ON (skills.id = characters_skills.skill_id)
            WHERE characters.id = :id"
        );
        $stmt -> execute(array('id' => $id));


        while($row = $stmt -> fetch()) {
            $skills[$row['id']] = $row;
        }

        return $skills;
    }

    /**
     * Fill char skills with random
     * @param $char_id
     */
    protected function fillSkillsForCharacter($char_id)
    {
        $db = $this -> container['db'];

        $stmt = $db -> query('SELECT id, name FROM `skills`');

        while ($skill = $stmt -> fetch()) {
            $stmt_skill = $db -> prepare(
                "INSERT INTO  `characters_skills` SET `character_id` = :character_id, `skill_id` = :skill_id, `value` = :value"
            );
            $stmt_skill -> execute(
                array(
                    'character_id' => $char_id,
                    'skill_id' => $skill['id'],
                    'value' => rand(0, 50)
                )
            );
        }
    }

    public function getFromCache($id, $column = 'id')
    {
        $char = NULL;

        if ($column == 'id' && isset(self::$cacheById[$id])) {
            $char = self::$cacheById[$id];
        } else {
            if ($data = $this -> container['cache'] -> get(self::CHAR_CACHE_NAME_PREFIX . $column . '_' . $id)) {
                $char = new Model($data);
                self::$cacheById[$char -> getId()] = $char;
            }
        }

        return $char;
    }

    public function setToCache(Model $char)
    {
        if ($char -> isExist()) {
            $dump = $char -> dump();
            $this -> container['cache'] -> set(self::CHAR_CACHE_NAME_PREFIX . 'id_' . $char -> getId(), $dump);
            $this -> container['cache'] -> set(self::CHAR_CACHE_NAME_PREFIX . 'login_' . $char -> getLogin(), $dump);
            self::$cacheById[$char -> getId()] = $char;
        }
    }

    public function clearFromCache(Model $char)
    {
        $this -> container['cache'] -> delete(self::CHAR_CACHE_NAME_PREFIX . 'id_' . $char -> getId());
        $this -> container['cache'] -> delete(self::CHAR_CACHE_NAME_PREFIX . 'login_' . $char -> getLogin());

        if (isset(self::$cacheById[$char -> getId()])) {
            unset(self::$cacheById[$char -> getId()]);
        }
    }

}