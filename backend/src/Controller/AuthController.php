<?php

namespace Controller;

use Core\Session;
use Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;

readonly class AuthController
{
    public function __construct(
        private UserRepository $userRepository,
        private Session $session
    ) {}

    public function login(): void
    {
        require __DIR__ . '/../../views/pages/auth/login.php';
    }

    #[NoReturn]
    public function authenticate(): void
    {
        $identifiant = $_POST['identifiant'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userRepository->findByIdentifiant($identifiant);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->session->flash('error', 'Identifiants invalides');
            header('Location: /login');
            exit;
        }

        unset($user['password']);
        $this->session->set('user', $user);

        /*if ($this->session->isAdmin()) {
            header('Location: /users');
        } else {
            header('Location: /dashboard');
        }*/
        header('Location: /dashboard');
        exit;
    }

    #[NoReturn]
    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }

}