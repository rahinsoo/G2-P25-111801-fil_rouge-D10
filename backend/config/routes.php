<?php

use Core\Router;
use Controller\AppController;
use Controller\CustomerController;
use Controller\UserController;
use Controller\TaskController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\PasswordController;
use Controller\API\UserApiController;

return function (
    Router $router,
    AppController $controller,
    CustomerController $customerController,
    UserController $userController,
    AuthController $authController,
    DashboardController $dashboardController,
    PasswordController $passwordController,
    UserApiController $userApiController,
) {

    // ============================================
    // ROUTE PRINCIPALE
    // ============================================

    $router->get('/', function () use ($controller, $authController) {
        if (isset($_SESSION['user'])) {
            $controller->home();
        } else {
            $authController->login();
        }
    });

    // ============================================
    // ROUTES HOME
    // ============================================

    $router->get('/home', function () use ($controller) {
        $controller->home();
    });

    $router->get('/pagetest', function () use ($controller) {
        $controller->pagetest();
    });

    // ============================================
    // ROUTES CUSTOMER
    // ============================================

    $router->get('/customer/listCustomer', function () use ($customerController) {
        $customerController->listClient();
    });

    // ============================================
    // ROUTES USERS
    // ============================================

    $router->get('/users', function () use ($userController) {
        $userController->index();
    });

    $router->get('/users/create', function () use ($userController) {
        $userController->create();
    });

    $router->post('/users/store', function () use ($userController) {
        $userController->store();
    });

    $router->get('/users/edit/(\d+)', function ($matches) use ($userController) {
        $userController->edit((int) $matches[1]);
    });

    $router->post('/users/update/(\d+)', function ($matches) use ($userController) {
        $userController->update((int) $matches[1]);
    });

    $router->post('/users/delete/(\d+)', function ($matches) use ($userController) {
        $userController->delete((int) $matches[1]);
    });

    $router->get('/users/(\d+)/change-password', function ($matches) use ($userController) {
        $userController->changePassword((int) $matches[1]);
    });

    $router->post('/users/(\d+)/update-password', function ($matches) use ($userController) {
        $userController->updatePassword((int) $matches[1]);
    });

    // ============================================
    // ROUTES AUTH
    // ============================================

    $router->get('/login', function () use ($authController) {
        $authController->login();
    });

    $router->post('/login', function () use ($authController) {
        $authController->authenticate();
    });

    $router->get('/logout', function () use ($authController) {
        $authController->logout();
    });

    // ============================================
    // MOT DE PASSE OUBLIÉ
    // ============================================

    $router->get('/forgot-password', function () use ($passwordController) {
        $passwordController->forgot();
    });

    $router->post('/forgot-password', function () use ($passwordController) {
        $passwordController->reset();
    });

    // ============================================
    // API
    // ============================================

    $router->get('/api/users', function () use ($userApiController) {
        $userApiController->index();
    });

    $router->get('/api/users/(\d+)', function ($matches) use ($userApiController) {
        $userApiController->show((int) $matches[1]);
    });

    $router->post('/api/users', function () use ($userApiController) {
        $userApiController->store();
    });

    $router->put('/api/users/(\d+)', function ($matches) use ($userApiController) {
        $userApiController->update((int) $matches[1]);
    });

    $router->patch('/api/users/(\d+)', function ($matches) use ($userApiController) {
        $userApiController->update((int) $matches[1]);
    });

    $router->delete('/api/users/(\d+)', function ($matches) use ($userApiController) {
        $userApiController->destroy((int) $matches[1]);
    });
};
