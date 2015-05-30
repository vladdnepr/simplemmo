<?php 
namespace VladDnepr\SimpleMMO\Repository;
 
use VladDnepr\SimpleMMO\Model\Character as Char;
use VladDnepr\SimpleMMO\Model\Combat as Model;

class Combat {

    const COMBAT_CACHE_PREFIX = 'combat_';

    protected $container;

    function __construct(\Pimple\Container $container)
    {
        $this -> container = $container;
    }

    public function getNewCombat()
    {
        return new Model();
    }

    public function getPlayerCombat($player)
    {
        $combat = NULL;

        $data = $this -> container['cache'] -> get(
            $this -> getCacheKey($player)
        );

        if ($data) {
            $combat = new Model($data);
            $combat -> setPlayer1($this -> container['character_repository'] -> getByID($combat -> getP1id()));
            $combat -> setPlayer2($this -> container['character_repository'] -> getByID($combat -> getP2id()));
        }

        return $combat;
    }

    public function persist(Model $combat)
    {
        $this -> container['cache'] -> set(
            $this -> getCacheKey($combat -> getPlayer1()),
            $combat -> dump()
        );
    }

    public function clear(Model $combat)
    {
        $this -> container['cache'] -> delete(
            $this -> getCacheKey($combat -> getPlayer1())
        );
    }

    public function getCacheKey(Char $player)
    {
        return self::COMBAT_CACHE_PREFIX . $player -> getId();
    }
}