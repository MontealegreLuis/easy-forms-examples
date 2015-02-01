<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
chdir(__DIR__ . '/../');

require 'vendor/autoload.php';

$app = new \Slim\Slim();

$container = new \Application\Container(require 'app/config.php');
$container->configure($app);

$router = new \Application\Router();
$router->register($app);

$app->run();
