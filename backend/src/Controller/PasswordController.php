<?php

namespace Controller;

use Core\Session;
use Core\Response;
use Repository\UserRepository;

use JetBrains\PhpStorm\NoReturn;

readonly class PasswordController
{
    public function __construct(
        private UserRepository $userRepository,
        private Session $session,
        private Response $response
    ) {}

    public function forgot(): void
    {
        /*require __DIR__ . '/../../views/pages/auth/forgot-password.php';*/
        $this->response->render('auth/forgot-password');
    }

    #[NoReturn]
    public function reset(): void
    {
        $identifiant = $_POST['identifiant'] ?? '';
        $user = $this->userRepository->findByIdentifiant($identifiant);

        if (!$user) {
            $this->session->flash('error', 'Identifiant introuvable');
            /*header('Location: /forgot-password');
            exit;*/
            $this->response->redirect('/forgot-password');
        }

        // Ici, on ne demande pas l’ancien mot de passe
        $newPassword = $_POST['new_password'] ?? '';
        if (!$newPassword) {
            $this->session->flash('error', 'Veuillez saisir un nouveau mot de passe');
            /*header('Location: /forgot-password');
            exit;*/
            $this->response->redirect('/forgot-password');
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userRepository->updatePassword($user['id_user'], $hashedPassword);

        $this->session->flash('success', 'Mot de passe réinitialisé avec succès');
        /*header('Location: /login');
        exit;*/
        $this->response->redirect('/login');
    }
}