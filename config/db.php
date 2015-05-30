<?php

return array(
    'dsn' => 'mysql:host=127.0.0.1;dbname=simplemmo;charset=utf8',
    'user' => 'simplemmo',
    'password' => 'simplemmo',
    'options' => array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    )
);