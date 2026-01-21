<?php

namespace Controller;

use Model\Affectation;
use Repository\AffectationRepository;
use Repository\ActiviteRepository;
use Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;
use Core\Request;
use Core\Response;
use Core\Session;

readonly class AffectationController
{
    public function __construct(
        private AffectationRepository $affectationRepository,
        private UserRepository        $userRepository,
        private ActiviteRepository    $activiteRepository,
        private Session               $session
    ) {}

    /*private function denyIfNotAdmin(): void
    {
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        if (!$this->session->isAdmin()) {
            header('Location: /dashboard');
            exit;
        }
    }*/

    /// Liste toutes les affectations ///
    public function index(): void
    {
        /*$this->denyIfNotAdmin();*/

        $affectations = $this->affectationRepository->findAllWithDetails();

        // Vue : list des affectations ou formulaire
        require __DIR__ . '/../../views/pages/affectations/list.php';
    }

    /// Formulaire pour créer une nouvelle affectation ///
    public function create(): void
    {
        /*$this->denyIfNotAdmin();*/

        $users = $this->userRepository->readAll();
        $activites = $this->activiteRepository->readAll();

        require __DIR__ . '/../../views/pages/affectations/create.php';
    }

    #[NoReturn]
    public function store(): void
    {
        /*$this->denyIfNotAdmin();*/

        $id_user = (int) $_POST['id_user'];
        $id_activite = (int) $_POST['id_activite'];
        $tjm = (float) $_POST['tjm'];

        $affectation = new Affectation($id_user, $id_activite, $tjm);

        try {
            $this->affectationRepository->affecter($affectation);
            $this->session->flash('success', 'Utilisateur affecté avec succès.');
        } catch (\RuntimeException $e) {
            $this->session->flash('error', $e->getMessage());
        }

        header('Location: /affectations');
        exit;
    }

    /// Supprimer une affectation ///
    #[NoReturn]
    public function delete(int $id_user, int $id_activite): void
    {
        /*$this->denyIfNotAdmin();*/

        $this->affectationRepository->delete($id_user, $id_activite);
        $this->session->flash('success', 'Affectation supprimée avec succès.');

        header('Location: /affectations');
        exit;
    }

    /// mise à jour TJM ///
    #[NoReturn]
    public function updateTjm(int $id_user, int $id_activite): void
    {
        /*$this->denyIfNotAdmin();*/

        $tjm = (float) $_POST['tjm'];
        $this->affectationRepository->updateTjm($id_user, $id_activite, $tjm);

        $this->session->flash('success', 'TJM mis à jour avec succès.');
        header('Location: /affectations');
        exit;
    }
}