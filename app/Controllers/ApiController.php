<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ApiController {
    protected $view;

    public function __construct(Twig $view) {
        $this->view = $view;
    }
}
