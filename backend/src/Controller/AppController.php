<?php

namespace Controller;

use Core\Request;
use Core\Session;
use Helper\Debug;
use JetBrains\PhpStorm\NoReturn;
use Core\Response;
use Repository\HomeRepository;

require_once __DIR__ . '/../Helper/Debug.php';

final readonly class AppController {

    public function __construct(
        private Response $response,
        private HomeRepository $homeRepository,
        //private Session $session,
        //private Request $request,
    ) {}

    public function home() : void {
        $client = $this->homeRepository->findAllClients(3);

        $this->response->render('home', [
            'featuredClient' => $client,
            'total' => $this->homeRepository->countAll()
        ]);
    }

    public function notFound() : void {
        $this->response->render('not-found', [], 404);
    }


}