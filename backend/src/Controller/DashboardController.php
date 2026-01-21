<?php

namespace Controller;

use Core\Session;

readonly class DashboardController
{
    public function __construct(private Session $session) {}

    public function index(): void
    {
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        $user = $this->session->get('user');
        require __DIR__ . '/../../views/pages/dashboard/index.php';
    }

}