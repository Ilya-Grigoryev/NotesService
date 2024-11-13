<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\Database;

class ApiController {
    protected $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function get_notes(Request $request, Response $response) {
        $notes = $this->db->get_notes();
        $response->getBody()->write(json_encode($notes));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function get_note_by_id(Request $request, Response $response, array $args) {
        $id = (int)$args['id'];
        $note = $this->db->get_note_by_id($id);
        if ($note) {
            $response->getBody()->write(json_encode($note));
        } else {
            $response->getBody()->write(json_encode(['status' => 400, 'error' => 'Note not found']));
            return $response->withStatus(404);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function add_note(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $title = $data['title'] ?? null;
        $content = $data['content'] ?? null;

        if ($title && $content) {
            $newNote = $this->db->add_note($title, $content);
            $response->getBody()->write(json_encode($newNote));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['status' => 400, 'error' => 'Title and content are required']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function delete_note_by_id(Request $request, Response $response, array $args) {
        $id = (int)$args['id'];
        $note = $this->db->get_note_by_id($id);
        if ($note) {
            $this->db->delete_note_by_id($id); 
            $response->getBody()->write(json_encode(['status' => 200, 'message' => 'Note deleted successfully']));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['status' => 400, 'error' => 'Note not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
    }

    public function edit_note_by_id(Request $request, Response $response, array $args) {
        $id = (int)$args['id'];
        $data = $request->getParsedBody();
        $title = $data['title'] ?? null;
        $content = $data['content'] ?? null;

        if ($id && $title && $content) {
            $editedNote = $this->db->edit_note_by_id($id, $title, $content);
            $response->getBody()->write(json_encode($editedNote));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['status' => 400, 'error' => 'Id, title and content are required']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}
