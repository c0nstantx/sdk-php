<?php

if (!is_file($autoloadFile =  __DIR__.'/../vendor/autoload.php')) {
    throw new \LogicException('autoload.php could not be found in vendor folder. Please run "composer install"');
}

require $autoloadFile;
