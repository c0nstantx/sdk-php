<?php

chdir(__DIR__);
require __DIR__.'/../vendor/autoload.php';

$command = new \RG\Command\AuthCommand();

$command->execute();