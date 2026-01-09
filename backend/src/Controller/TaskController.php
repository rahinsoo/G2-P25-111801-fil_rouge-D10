<?php

namespace Controller;

use Core\Auth;
use Model\TaskModel;

class TaskController
{
    public function index()
    {
        Auth::check();

        $tasks = TaskModel::findByUser($_SESSION['user']['id']);

        require __DIR__ . '/../../views/tasks/index.php';
    }
}
