<?php

use Controller\UserController;
use Core\Router;

return function (Router $router, UserController $userController) {

    // Page d'accueil ou dashboard
    $router->get('/', [$userController, 'index']);

    // CRUD utilisateurs
    $router->get('/users', [$userController, 'index']); // liste
    $router->get('/users/create', [$userController, 'create']); // formulaire création
    $router->post('/users/store', [$userController, 'store']); // envoi création
    $router->get('/users/edit/(\d+)', function($matches) use ($userController) {
        $userController->edit((int)$matches[1]);
    });
    $router->post('/users/update/(\d+)', function($matches) use ($userController) {
        $userController->update((int)$matches[1]);
    });
    $router->post('/users/delete/(\d+)', function($matches) use ($userController) {
        $userController->delete((int)$matches[1]);
    });

    $router->get('/users/(\d+)/change-password', function($matches) use ($userController) {
        $userController->changePassword((int)$matches[1]);
    });

    $router->post('/users/(\d+)/update-password', function($matches) use ($userController) {
        $userController->updatePassword((int)$matches[1]);
    });

};
