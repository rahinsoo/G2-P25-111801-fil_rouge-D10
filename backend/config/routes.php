<?php

use Controller\UserController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\PasswordController;

use Core\Router;

return function (
    Router $router,
    UserController $userController,
    AuthController $authController,
    DashboardController $dashboardController,
    PasswordController $passwordController)
{
    // routes pour CRUD utilisateurs
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

    // routes pour l'authentification/connexion
    $router->get('/', [$authController, 'login']);
    $router->get('/login', [$authController, 'login']);
    $router->post('/login', [$authController, 'authenticate']);
    $router->get('/logout', [$authController, 'logout']);

    // routes pour mot de passe oublié
    $router->get('/forgot-password', [$passwordController, 'forgot']);
    $router->post('/forgot-password', [$passwordController, 'reset']);

    // routes pour la page principale Dashboard
    $router->get('/dashboard', [$dashboardController, 'index']);
};
