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

    public function create()
    {
        Auth::check();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            TaskModel::create(
                $_POST['title'],
                $_POST['description'] ?? '',
                $_SESSION['user']['id']
            );
            header('Location: /tasks');
            exit;
        }

        require __DIR__ . '/../../views/tasks/create.php';
    }

   public function edit($id = null)
{
    Auth::check();

    if (!$id) {
        die('ID manquant');
    }

    $task = TaskModel::findOneByUser($id, $_SESSION['user']['id']);
    if (!$task) {
        die('Tâche non trouvée ou accès interdit');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        TaskModel::update($id, $_POST['title'], $_POST['description'] ?? '');
        header('Location: /tasks');
        exit;
    }

    require __DIR__ . '/../../views/tasks/edit.php';
}


public function delete($id = null)
{
    Auth::check();

    if (!$id) {
        die('ID manquant');
    }

    $task = TaskModel::findOneByUser($id, $_SESSION['user']['id']);
    if (!$task) {
        die('Tâche non trouvée ou accès interdit');
    }

    TaskModel::delete($id);
    header('Location: /tasks');
    exit;
}

?>
