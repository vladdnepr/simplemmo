<?php

namespace VladDnepr\SimpleMMO\Provider;

use VladDnepr\SimpleMMO\Model\Character;
use VladDnepr\SimpleMMO\Model\Combat;

class Model implements \Pimple\ServiceProviderInterface
{
    public function register(\Pimple\Container $pimple)
    {
        $pimple['model_character'] = $pimple -> factory(function ($c) {
            return new Character();
        });

        $pimple['model_combat'] = $pimple -> factory(function ($c) {
            return new Combat();
        });
    }
}