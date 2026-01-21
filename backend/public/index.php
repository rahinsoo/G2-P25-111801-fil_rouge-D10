<?php

use Controller\AppController;
use Controller\UserController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\PasswordController;
use Controller\API\UserApiController;
use Controller\PingApiController;

use Repository\HomeRepository;
use Repository\CustomerRepository;
use Repository\RoleRepository;
use Repository\UserRepository;

use Core\Cors;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\Router;
use Core\Session;

session_start();
require __DIR__ . '/../autoload.php';
$config = require __DIR__ . '/../config/db.php';

Cors::handle();

$session = new Session();
$request = new Request();
$response = new Response();
$router = new Router();

$pdo = Database::makePdo($config['db']);

$homeRepository = new HomeRepository($pdo);
$customerRepository = new CustomerRepository($pdo);
$userRepository = new UserRepository($pdo);
$roleRepository = new RoleRepository($pdo);

$appController = new AppController($response, $homeRepository, $customerRepository);
$authController = new AuthController($userRepository, $session);
$userController = new UserController($userRepository, $roleRepository, $session);
$dashboardController = new DashboardController($session);
$passwordController = new PasswordController($userRepository, $session);
$userApiController = new UserApiController($userRepository, $session);

$pingApiController = new PingApiController();

$registerRoutes = require __DIR__ . '/../config/routes.php';

$registerRoutes(
    $router,
    $appController,
    $userController,
    $authController,
    $dashboardController,
    $passwordController,
    $userApiController,
    $pingApiController
);

$router->dispatch($request, $response);
