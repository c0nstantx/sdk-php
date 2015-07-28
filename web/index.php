<?php

use Symfony\Component\HttpFoundation\Request;
use RG\Kernel;

require __DIR__.'/../vendor/autoload.php';

$configPath = __DIR__.'/../app/config/';

$request = Request::createFromGlobals();
$kernel = new Kernel($configPath);
$kernel->loadContainer();
$response = $kernel->handle($request);
$response->send();
