<?php

namespace Controller;

use JetBrains\PhpStorm\NoReturn;
use Core\Request;
use Core\Response;
use Core\Session;
use Repository\CustomerRepository;

readonly class CustomerController
{
    public function __construct(
        private Response           $response,
        private CustomerRepository $customerRepository,
        private Session            $session,
        private Request            $request
    )
    {}

//    public function customer() : void
//    {
//        // ✅ SÉCURISATION
//        if (!$this->session->isLogged()) {
//            header('Location: /login');
//            exit;
//        }
//
//        // ✅ GESTION POST :  création d'entreprise
//        if ($this->request->getMethod() === 'POST') {
//            $this->createCustomer();
//            return;
//        }
//
//        // ✅ AFFICHAGE GET : liste des clients
//        $clients = $this->customerRepository->findAllClients();
//        $this->response->render('customer/listCustomer', [
//            'listClient' => $clients
//        ]);
//    }

    // GET /customers
    public function listClient(): void
    {
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        $clients = $this->customerRepository->findAllClients();
        $this->response->render('customer/listCustomer', [
            'listClient' => $clients
        ]);
    }

    public function edit(): void
    {
        if (!$this->session->isLogged()) {
            header('Location: /login');
            exit;
        }

        $edit = $this->customerRepository->updateClient();
        $this->response->render('customer/listCustomer', [
            'editClient' => $edit
        ]);
    }
    private function createCustomer(): void
    {
        // Récupération des données du formulaire
        $nom = $this->request->getPostParam('nom');
        $numero_siren = $this->request->getPostParam('numero_SIREN');
        $type = $this->request->getPostParam('type');
        $information = $this->request->getPostParam('information') ?? '';
        $adresse = $this->request->getPostParam('adresse');
        $is_facturable = $this->request->getPostParam('is_facturable') === 'on' ||
            $this->request->getPostParam('is_facturable') === '1';

        // Validation basique
        if (empty($nom) || empty($numero_siren) || empty($type) || empty($adresse)) {
            $this->session->setFlash('error', 'Tous les champs obligatoires doivent être remplis.');
            header('Location: /customer/listCustomer');
            exit;
        }

        // Insertion en base
        $success = $this->customerRepository->createClient(
            nom: $nom,
            numero_siren: $numero_siren,
            type: $type,
            information: $information,
            is_facturable: $is_facturable,
            adresse:  $adresse
        );

        if ($success) {
            $this->session->setFlash('success', 'Entreprise créée avec succès !  ✅');
        } else {
            $this->session->setFlash('error', 'Erreur lors de la création de l\'entreprise.');
        }

        header('Location:  /customer/listCustomer');
        exit;
    }

}