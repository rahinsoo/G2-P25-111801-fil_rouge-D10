<?php

use Controller\AppController;
use Repository\HomeRepository;
use Repository\CustomerRepository;
use Repository\RoleRepository;
use Repository\UserRepository;
use Repository\TaskRepository;

use Controller\UserController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\PasswordController;
use Controller\API\UserApiController;
use Controller\TaskController;
use Controller\CustomerController;

use Core\Cors;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\Router;
use Core\Session;

session_start();
require __DIR__ . '/../autoload.php';
$config = require_once __DIR__ . '/../config/db.php';

Cors::handle();

$session = new Session();
$request = new Request();
$response = new Response();
$router = new Router();

$homeRepository = new HomeRepository(Database::makePdo($config['db']));
$CustomerRepository = new CustomerRepository(Database::makePdo($config['db']));
$userRepository = new UserRepository(Database::makePdo($config['db']));
$roleRepository = new RoleRepository(Database::makePdo($config['db']));
$taskRepository = new TaskRepository(Database::makePdo($config['db']));

$AppController = new AppController($response,$homeRepository, $CustomerRepository, $session, $request);
$authController = new AuthController($response, $userRepository, $session, $request);
$userController = new UserController($response, $userRepository, $roleRepository, $session, $request);
$dashboardController = new DashboardController($response, $session, $request);
$passwordController = new PasswordController($response, $userRepository, $session, $request);
$userApiController = new UserApiController($userRepository, $session, $request);
$taskController = new TaskController($response, $taskRepository, $session, $request);
$CustomerController = new CustomerController($response, $CustomerRepository, $session, $request);

$registerRoutes = require __DIR__ . '/../config/routes.php';
$registerRoutes($router, $AppController, $userController, $authController, $dashboardController, $passwordController, $userApiController, $taskController);
$router->dispatch($request, $response);


