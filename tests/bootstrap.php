<?php

$autoloader = require dirname(__DIR__) . '/vendor/autoload.php';
$autoloader->addPsr4('Amneale\\Slim\\ApiHandlers\\Tests\\', __DIR__);