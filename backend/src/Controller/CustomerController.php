<?php

namespace Controller;

use JetBrains\PhpStorm\NoReturn;
use Core\Request;
use Core\Response;
use Core\Session;
use Repository\CustomerRepository;

readonly class UserController
{
    public function __construct(
        private Response           $response,
        private CustomerRepository $customerRepository,
        private Session            $session,
        private Request            $request
    )
    {}

    private function denyIfNotLogged(): void
    {
        if (!$this->session->get('user')) {
            header('Location: /login');
            exit;
        }
    }

    #[NoReturn]
    public function create(): void
    {
        //$this->denyIfNotAdmin();

        $this->customerRepository->createClient(
            $_POST['nom'],
            $_POST['numero_SIREN'],
            $_POST['type'],
            $_POST['information'],
            $_POST['is_facturable'],
            $_POST['adresse']
        );

        header('Location: /customer/storeCustomer');
        exit;
    }

}