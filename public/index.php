<?php

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

use App\Controllers\FrontendController as FrontendController;
use App\Controllers\ApiController as ApiController;
use App\Services\Database;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
$app->add(TwigMiddleware::create($app, $twig));

$apiController = new ApiController();
$frontendController = new FrontendController($twig);


    // API
    $app->get('/api/notes', [$apiController, 'get_notes']);
    $app->get('/api/notes/{id}', [$apiController, 'get_note_by_id']);
    $app->post('/api/notes', [$apiController, 'add_note']);
    $app->delete('/api/notes/{id}', [$apiController, 'delete_note_by_id']);
    $app->post('/api/notes/{id}/edit', [$apiController, 'edit_note_by_id']);


    // Frontend
    $app->get('/', [$frontendController, 'home']);
    $app->get('/note/{id}', [$frontendController, 'edit_note']);

$app->run();