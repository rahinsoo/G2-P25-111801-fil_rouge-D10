<?php

use Controller\AppController;
use Core\Request;
use Core\Response;
use Controller\UserController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\PasswordController;
use Controller\API\UserApiController;
use Controller\ActiviteController;

use Core\Router;

return function (
    Router $router,
    AppController $controller,
    UserController $userController,
    AuthController $authController,
    DashboardController $dashboardController,
    PasswordController $passwordController,
    UserApiController $userApiController,
    ActiviteController $activiteController
)
{
    // routes home et customer
    $router->get('/', [$controller, 'home']);
    $router->get('/home', [$controller, 'home']);
    $router->get('/customer/listCustomer', [$controller, 'customer']);
    $router->get('/pagetest', [$controller, 'pagetest']);
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

    // route test API //
    $router->post('/api/test-login', function() {
        $_SESSION['user'] = [
            'id_user' => 1,
            'id_user_role' => 1,
            'role' => 'admin'
        ];
        echo json_encode(['ok' => true, 'role' => 'admin']);
    });

    $router->delete('/api/test', function () {
        echo json_encode(['ok' => true]);
    });

    $router->get('/api/users', function() use ($userApiController) {
        $userApiController->index();
    });

    $router->get('/api/users/(\d+)', function($matches) use ($userApiController) {
        $userApiController->show((int)$matches[1]);
    });

    $router->post('/api/users', function() use ($userApiController) {
        $userApiController->store();
    });

    $router->put('/api/users/(\d+)', function($matches) use ($userApiController) {
        $userApiController->update((int)$matches[1]);
    });

    $router->patch('/api/users/(\d+)', function($matches) use ($userApiController) {
        $userApiController->update((int)$matches[1]);
    });

    $router->delete('/api/users/(\d+)', function ($matches) use ($userApiController) {
        $userApiController->destroy((int)$matches[1]);
    });

    /// routes pour les activités ///
    $router->get('/activites', [$activiteController, 'index']); // liste
    $router->get('/activites/create', [$activiteController, 'create']); // formulaire création
    $router->post('/activites/store', [$activiteController, 'store']); // envoi création
};