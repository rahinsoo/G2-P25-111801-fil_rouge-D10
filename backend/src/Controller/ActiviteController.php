<?php

namespace Controller;

use Model\Activite;
use Repository\CustomerRepository;
use Repository\ActiviteRepository;
use JetBrains\PhpStorm\NoReturn;
use Core\Request;
use Core\Response;
use Core\Session;

readonly class ActiviteController
{
    public function __construct(
        private ActiviteRepository $activiteRepository,
        private CustomerRepository $customerRepository,
        /*private Session        $session*/
    ) {}

    public function index(): void
    {
        /*$this->denyIfNotAdmin();*/
        $activites = $this->activiteRepository->readAll();
        require __DIR__ . '/../../views/pages/activites/list.php';
    }

    public function create(): void
    {
        /*$this->denyIfNotAdmin();*/
        $nomsClients = $this->customerRepository->findAllClients();
        require __DIR__ . '/../../views/pages/activites/create.php';
    }

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

        header('Location: /activites');
        exit;
    }

    public function edit(int $id_activite): void
    {
        /*$this->denyIfNotAdmin();*/
        $nomsClients = $this->customerRepository->findAllClients();
        $activite = $this->activiteRepository->readOne($id_activite);
        require __DIR__ . '/../../views/pages/activites/edit.php';
    }

    #[NoReturn]
    public function update(int $id_activite): void
    {
        /*$this->denyIfNotAdmin();*/
        $activite = $this->activiteRepository->readOne($id_activite);
        if (!$activite) {
            header('Location: /activites');
            exit;
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

        header('Location: /activites');
        exit;
    }

    #[NoReturn]
    public function delete(int $id_activite): void
    {
        /*$this->denyIfNotAdmin();*/
        $this->activiteRepository->deleteActivite($id_activite);
        header('Location: /activites');
        exit;
    }
}