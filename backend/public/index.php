<?php

require __DIR__ . '/../vendor/autoload.php';

use Core\Router;
use Core\Request;
use Core\Response;
use Core\Session;
use Core\Database;

use Controller\AppController;
use Controller\CustomerController;
use Controller\UserController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\PasswordController;
use Controller\Api\UserApiController;
use Controller\TaskController;

use Repository\CustomerRepository;
use Repository\UserRepository;
use Repository\TaskRepository;

// Configuration DB
$config = require __DIR__ . '/../config/db.php';

// Core instances
$request = new Request();
$response = new Response();
$session = new Session();

$pdo = Database::makePdo($config['db']);

// Repositories
$customerRepository = new CustomerRepository($pdo);
$userRepository = new UserRepository($pdo);
$taskRepository = new TaskRepository($pdo);

// Controllers
$appController = new AppController($response);
$customerController = new CustomerController($response, $customerRepository);
$userController = new UserController($response, $userRepository);
$authController = new AuthController($response, $userRepository, $session);
$dashboardController = new DashboardController($response, $session);
$passwordController = new PasswordController($response, $userRepository);
$userApiController = new UserApiController($response, $userRepository);
$taskController = new TaskController($response, $taskRepository, $session, $request);

$router = new Router($request, $response);

// Routes
$registerRoutes = require __DIR__ . '/../config/routes.php';

$registerRoutes(
    $router,
    $appController,
    $customerController,
    $userController,
    $authController,
    $dashboardController,
    $passwordController,
    $userApiController,
    $taskController
);

// Resolve
$router->resolve();
