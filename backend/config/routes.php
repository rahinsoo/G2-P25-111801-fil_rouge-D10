<?php

use Core\Router;
use Core\Request;
use Core\Response;

use Controller\AppController;
use Controller\UserController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\PasswordController;
use Controller\API\UserApiController;
use Controller\PingApiController;
use Controller\TaskController;

return function (
    Router $router,
    AppController $appController,
    UserController $userController,
    AuthController $authController,
    DashboardController $dashboardController,
    PasswordController $passwordController,
    UserApiController $userApiController,
    PingApiController $pingApiController
) {

    $router->get('/', function() use ($appController) {
        $appController->home();
    });

    $router->get('/add', function() use ($appController) {
        $appController->add();
    });

    $router->post('/add', function() use ($appController) {
        $appController->handleAddGame();
    });

    $router->get('/games', function() use ($appController) {
        $appController->games();
    });

    $router->get('/random', function() use ($appController) {
        $appController->random();
    });

    $router->get('/login', function() use ($authController) {
        $authController->login();
    });

    $router->post('/login', function() use ($authController) {
        $authController->login();
    });

    $router->get('/logout', function() use ($authController) {
        $authController->logout();
    });

    $router->get('/dashboard', function() use ($dashboardController) {
        $dashboardController->index();
    });

    $router->get('/password', function() use ($passwordController) {
        $passwordController->index();
    });

    $router->post('/password', function() use ($passwordController) {
        $passwordController->index();
    });

    $router->get('/users', function() use ($userController) {
        $userController->index();
    });

    $router->get('/api/ping', function() use ($pingApiController) {
        $pingApiController->ping();
    });

    $router->getRegex('#^/games/(\d+)$#', function (Request $req, Response $res, array $m) use ($appController) {
        $appController->gameById((int)$m[1]);
    });

    $taskController = new TaskController();

    $router->get('/tasks', function() use ($taskController) {
        $taskController->index();
    });

    $router->get('/tasks/create', function() use ($taskController) {
        $taskController->create();
    });

    $router->post('/tasks/create', function() use ($taskController) {
        $taskController->create();
    });

    $router->get('/tasks/edit/{id}', function(Request $req) use ($taskController) {
        $taskController->edit($req);
    });

    $router->post('/tasks/edit/{id}', function(Request $req) use ($taskController) {
        $taskController->edit($req);
    });

    $router->post('/tasks/delete/{id}', function(Request $req) use ($taskController) {
        $taskController->delete($req);
    });
};
