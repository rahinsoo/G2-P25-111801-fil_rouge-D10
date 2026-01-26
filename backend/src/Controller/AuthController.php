<?php

/// afficher la page login et vérifier les identifiants ///

namespace Controller;

use Core\Session;
use Core\Request;
use Core\Response;
use Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;

readonly class AuthController
{
    /// le controller coordonne les outils qu'on lui donne, il ne crée rien ///
    public function __construct(
        private UserRepository $userRepository,
        private Session $session,
        private Request $request,
        private Response $response
    ) {}

    public function login(): void
    {
        /*require __DIR__ . '/../../views/pages/auth/login.php';*/
        $this->response->render('auth/login');
    }

    /// méthode appelée après soumission du formulaire ///
    #[NoReturn]
    public function authenticate(): void
    {
        $identifiant = $this->request->post('identifiant');
        $password = $this->request->post('password');

        $user = $this->userRepository->findByIdentifiant($identifiant);

        /// compare le mot de passe en clair avec le hash en BDD ///
        if (!$user || !password_verify($password, $user['password'])) {
            $this->session->flash('error', 'Identifiants invalides');
            /*header('Location: /login');
            exit;*/
            $this->response->redirect('/login');
        }

        /// on retire le mot de passe hashé ///
        unset($user['password']);

        /// on stocke le reste dans la session ///
        $this->session->set('user', $user);

        /*if ($this->session->isAdmin()) {
            header('Location: /users');
        } else {
            header('Location: /dashboard');
        }*/
        /*header('Location: /dashboard');
        exit;*/
        $this->response->redirect('/dashboard');
    }

    /// déconnexion, suppression de la session ///
    #[NoReturn]
    public function logout(): void
    {
        $this->session->destroy();
        /*header('Location: /login');
        exit;*/
        $this->response->redirect('/login');
    }

}