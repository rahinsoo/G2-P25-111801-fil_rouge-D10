<?php

/// protection de l'accès au dashboard ///

namespace Controller;

use Core\Session;
use Core\Response;

readonly class DashboardController
{
    /// le controller reçoit la session, il ne la crée pas ///
    public function __construct(private Session $session, private Response $response) {}

    public function index(): void
    {
        if (!$this->session->isLogged()) {
            /*header('Location: /login');
            exit;*/
            $this->response->redirect('auth/login');
        }

        /// récupération de l'user stocké en session ///
        $user = $this->session->get('user');
        $isAdmin = $this->session->isAdmin();
        /*require __DIR__ . '/../../views/pages/dashboard/index.php';*/
        $this->response->render('dashboard/index', [
            'user' => $user,
            'isAdmin' => $isAdmin
        ]);
    }

}