<?php

namespace Controller;

use Core\Request;
use Core\Response;
use Core\Session;
use Repository\TaskRepository;

class TaskController
{
    public function __construct(
        private Response $response,
        private TaskRepository $taskRepository,
        private Session $session,
        private Request $request
    ) {}

    private function denyIfNotLogged(): void
    {
        if (!$this->session->get('user')) {
            header('Location: /login');
            exit;
        }
    }

    public function index(): void
    {
        $this->denyIfNotLogged();

        $user = $this->session->get('user');
        $tasks = $this->taskRepository->findByUser($user['id_user']);

        $this->response->render('tasks/index', [
            'tasks' => $tasks
        ]);
    }

    public function create(): void
    {
        $this->denyIfNotLogged();

        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getBody();

            $this->taskRepository->create(
                $data['title'] ?? '',
                $data['description'] ?? '',
                $this->session->get('user')['id_user']
            );

            $this->response->redirect('/tasks');
        }

        $this->response->render('tasks/create');
    }

    public function edit(int $id): void
    {
        $this->denyIfNotLogged();

        $userId = $this->session->get('user')['id_user'];
        $task = $this->taskRepository->findOneByUser($id, $userId);

        if (!$task) {
            http_response_code(403);
            echo 'Accès interdit';
            exit;
        }

        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getBody();

            $this->taskRepository->update(
                $id,
                $data['title'] ?? '',
                $data['description'] ?? ''
            );

            $this->response->redirect('/tasks');
        }

        $this->response->render('tasks/edit', [
            'task' => $task
        ]);
    }

    public function delete(int $id): void
    {
        $this->denyIfNotLogged();

        $userId = $this->session->get('user')['id_user'];
        $task = $this->taskRepository->findOneByUser($id, $userId);

        if (!$task) {
            http_response_code(403);
            echo 'Accès interdit';
            exit;
        }

        $this->taskRepository->delete($id);
        $this->response->redirect('/tasks');
    }
}
