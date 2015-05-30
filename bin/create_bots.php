<?php
require __DIR__ . '/../src/bootstrap.php';

if (!isset($argv[1])) {
    echo "Please fill count of bots as first param\n";
    exit(1);
}

$count = $argv[1];

/* @var \Pimple\Container $container */
/* @var \VladDnepr\SimpleMMO\Repository\Character $repository */
$repository = $container['character_repository'];

for ($i = 0; $i < $count;$i++) {
    $bot = $repository -> getNewCharacter();

    $bot -> setLogin(uniqid("bot_"));
    $bot -> setName(uniqid("Bot "));
    $bot -> setLevel(rand(1, 100));

    $repository -> persist($bot);
}