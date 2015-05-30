<?php

require __DIR__ . '/../src/bootstrap.php';

$container['server'] -> handle($_SERVER["REQUEST_URI"]);