<?php

namespace App\Controllers;

use App\Services\Database;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FrontendController {
    private $view;
    private $db;

    public function __construct(Twig $view) {
        $this->view = $view;
        $this->db = new Database;
    }

    public function home(Request $request, Response $response) {
        $notes = $this->db->get_notes();
        return $this->view->render($response, 'home.twig', ['notes' => $notes]);
    }

    public function edit_note(Request $request, Response $response, array $args) {
        $id = (int)$args['id'];
        $note = $this->db->get_note_by_id($id)[0];
        return $this->view->render($response, 'edit_note.twig', ['note' => $note]);
    }
}
