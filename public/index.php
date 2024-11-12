<?php

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

use App\Controllers\FrontendController as FrontendController;
use App\Controllers\ApiController as ApiController;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
$app->add(TwigMiddleware::create($app, $twig));

$frontendController = new FrontendController($twig);


$app->get('/', [$frontendController, 'home']);

$app->run();