<?php

/// gestion de la logique applicative/métier pour le CRUD des activités ///

namespace Controller;

use Model\Activite;
use Repository\CustomerRepository;
use Repository\ActiviteRepository;
use JetBrains\PhpStorm\NoReturn;
use Core\Request;
use Core\Response;
use Core\Session;

/// readonly pour rendre le controller immuable, ///
/// les propriétés sont initialisées une fois ///
readonly class ActiviteController
{
    /// on donne les outils au controller, injection de dépendances ///
    public function __construct(
        private ActiviteRepository $activiteRepository,
        private CustomerRepository $customerRepository,
        private Response $response,
        /*private Session        $session*/
    ) {}

    /// le controller récupère les données Activités,
    /// et charge la vue list.php qui utilise $activites
    public function index(): void
    {
        /*$this->denyIfNotAdmin();*/
        $activites = $this->activiteRepository->readAll();
        /*require __DIR__ . '/../../views/pages/activites/list.php';*/
        $this->response->render('activites/list', [
            'activites' => $activites
        ]);
    }

    /// création d'une activité, attribuée à un client (jointure dans le Repo)
    /// affichage du formulaire
    public function create(): void
    {
        /*$this->denyIfNotAdmin();*/
        $nomsClients = $this->customerRepository->findAllClients();
        /*require __DIR__ . '/../../views/pages/activites/create.php';*/
        $this->response->render('activites/create', [
            'nomsClients' => $nomsClients
        ]);
    }

    /// envoi des données du formulaire de création à la BDD ///
    #[NoReturn]
    public function store(): void
    {
        /*$this->denyIfNotAdmin();*/

        $this->activiteRepository->createActivite(
            $_POST['nom'],
            $_POST['description'],
            new \DateTimeImmutable($_POST['date_creation']),
            new \DateTimeImmutable($_POST['date_fin']),
            $_POST['statut'],
            (int)$_POST['id_client']
        );

        /*header('Location: /activites');
        exit;*/
        $this->response->redirect('/activites'); // redirection après la soumission du formulaire
    }

    /// affichage du formulaire pré-rempli de modification ///
    public function edit(int $id_activite): void
    {
        $activite = $this->activiteRepository->readOne($id_activite);
        if (!$activite) {
            $this->response->redirect('/activites');
        }

        /*$this->denyIfNotAdmin();*/

        $nomsClients = $this->customerRepository->findAllClients();

        /*require __DIR__ . '/../../views/pages/activites/edit.php';*/
        $this->response->render('activites/edit', [
            'activite'     => $activite,
            'nomsClients'  => $nomsClients
        ]);
    }

    /// envoi des données modifiées à la BDD ///
    #[NoReturn]
    public function update(int $id_activite): void
    {
        /*$this->denyIfNotAdmin();*/
        $activite = $this->activiteRepository->readOne($id_activite);
        if (!$activite) {
            /*header('Location: /activites');
            exit;*/
            $this->response->redirect('/activites'); // redirection si l'id de l'activité demandée n'existe pas
        }

        $updatedActivite = new Activite(
            $id_activite,
            $_POST['nom'],
            $_POST['description'],
            new \DateTimeImmutable($_POST['date_creation']),
            new \DateTimeImmutable($_POST['date_fin']),
            $_POST['statut'],
            (int)$_POST['id_client'],
            $activite->getNomClient()
        );

        $this->activiteRepository->updateActivite($updatedActivite);

        /*header('Location: /activites');
        exit;*/
        $this->response->redirect('/activites'); // redirection après soumission du formulaire

    }

    /// envoi de la demande de suppression en BDD ///
    #[NoReturn]
    public function delete(int $id_activite): void
    {
        /*$this->denyIfNotAdmin();*/
        $this->activiteRepository->deleteActivite($id_activite);
        /*header('Location: /activites');
        exit;*/
        $this->response->redirect('/activites');
    }
}