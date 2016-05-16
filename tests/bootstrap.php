<?php
require_once __DIR__.'/../bootstrap/autoload.php';

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
//    'debug' => true,
    'includePaths' => [__DIR__.'/../src', __DIR__.'/../vendor/tymon/jwt-auth/src/'],
    'excludePaths' => [__DIR__, __DIR__.'/../database/migrations',  __DIR__.'/../vendor/phpunit/phpunit/src']
]);
