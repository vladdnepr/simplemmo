<?php

namespace VladDnepr\SimpleMMO\Provider;

use VladDnepr\SimpleMMO\Repository\Character;
use VladDnepr\SimpleMMO\Repository\Combat;

class Model implements \Pimple\ServiceProviderInterface
{
    public function register(\Pimple\Container $container)
    {
        $container['character_repository'] = $container -> factory(function ($c) {
            return new Character($c);
        });

        $container['combat_repository'] = $container -> factory(function ($c) {
            return new Combat($c);
        });
    }
}