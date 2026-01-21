<?php


namespace Controller;

use Model\User;
use Repository\RoleRepository;
use Repository\TaskRepository;
use Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;
use Core\Request;
use Core\Response;
use Core\Session;

readonly class TaskController
{
    public function __construct(
        private Response       $response,
        private TaskRepository $userRepository,
        private Session        $session,
        private Request        $request
    )
        }