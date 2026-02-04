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
use Controller\AffectationController;

use Core\Router;

return function ( // fonction injectée par l'appli
    Router $router,
    AppController $controller,
    UserController $userController,
    AuthController $authController,
    DashboardController $dashboardController,
    PasswordController $passwordController,
    UserApiController $userApiController,
    ActiviteController $activiteController,
    AffectationController $affectationController
)
{
    // routes home et customer
    $router->get('/', [$controller, 'home']);
    $router->get('/home', [$controller, 'home']);
    $router->get('/customer/listCustomer', [$controller, 'customer']);
    $router->get('/pagetest', [$controller, 'pagetest']);

    /// routes pour CRUD utilisateurs ///
    $router->get('/users', [$userController, 'index']); // affichage de la liste
    $router->get('/users/create', [$userController, 'create']); // affichage du formulaire création utilisateur
    $router->post('/users/store', [$userController, 'store']); // envoi des données de création en BDD
    $router->get('/users/edit/(\d+)', function($matches) use ($userController) { // affichage du formulaire pré-rempli de modification utilisateur
        $userController->edit((int)$matches[1]);
    });
    $router->post('/users/update/(\d+)', function($matches) use ($userController) { // envoi des données de modification
        $userController->update((int)$matches[1]);
    });
    $router->post('/users/delete/(\d+)', function($matches) use ($userController) { // envoi des données de suppression
        $userController->delete((int)$matches[1]);
    });

    /// routes pour changer le password  ///
    $router->get('/users/(\d+)/change-password', function($matches) use ($userController) {
        $userController->changePassword((int)$matches[1]);
    });

    $router->post('/users/(\d+)/update-password', function($matches) use ($userController) {
        $userController->updatePassword((int)$matches[1]);
    });

    /// routes pour l'authentification/connexion ///
    $router->get('/', [$authController, 'login']);
    $router->get('/login', [$authController, 'login']);
    $router->post('/login', [$authController, 'authenticate']);
    $router->get('/logout', [$authController, 'logout']);

    /// routes pour mot de passe oublié ///
    $router->get('/forgot-password', [$passwordController, 'forgot']);
    $router->post('/forgot-password', [$passwordController, 'reset']);

    // routes pour la page principale Dashboard
    $router->get('/dashboard', [$dashboardController, 'index']);

    /// route test API ////
    $router->post('/api/test-login', function() { // route pour mimer la connexion sur postman
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

    /// routes UserAPi ///
    $router->get('/api/users', function() use ($userApiController) { // affichage de tous les utilisateurs
        $userApiController->index();
    });

    $router->get('/api/users/(\d+)', function($matches) use ($userApiController) { // affichage d'un seul utilisateur
        $userApiController->show((int)$matches[1]);
    });

    $router->post('/api/users', function() use ($userApiController) { // envoi données création
        $userApiController->store();
    });

    $router->put('/api/users/(\d+)', function($matches) use ($userApiController) { // modification complète, je dois mettre des values pour tous les paramètres
        $userApiController->update((int)$matches[1]);
    });

    $router->patch('/api/users/(\d+)', function($matches) use ($userApiController) { // modification partielle
        $userApiController->update((int)$matches[1]);
    });

    $router->delete('/api/users/(\d+)', function ($matches) use ($userApiController) { // suppression
        $userApiController->destroy((int)$matches[1]);
    });

    /// routes pour les activités ///
    $router->get('/activites', [$activiteController, 'index']); // affichage liste des activités
    $router->get('/activites/create', [$activiteController, 'create']); // formulaire création activité
    $router->post('/activites/store', [$activiteController, 'store']); // envoi création en BDD
    $router->get('/activites/edit/(\d+)', function($matches) use ($activiteController) { // formulaire pré-rempli modif activité
        $activiteController->edit((int)$matches[1]);
    });
    $router->post('/activites/update/(\d+)', function($matches) use ($activiteController) { // envoi modif activité en BDD
        $activiteController->update((int)$matches[1]);
    });
    $router->post('/activites/delete/(\d+)', function($matches) use ($activiteController) { // suppression
        $activiteController->delete((int)$matches[1]);
    });

    /// routes pour les affectations ///
    $router->get('/affectations', [$affectationController, 'index']); // liste des affectations
    $router->get('/affectations/create', [$affectationController, 'create']); // formulaire création affectation
    $router->post('/affectations/store', [$affectationController, 'store']); // envoi création de l'affectation en BDD
    ///$router->get('/affectations/edit/(\d+)', function($matches) use ($affectationController) { // formulaire modif du TJM
        ///$affectationController->edit((int)$matches[1]);
    ///});
    $router->post('/affectations/updateTjm/(\d+)/(\d+)', function($matches) use ($affectationController) { // envoi modif TJM en BDD
        $affectationController->updateTjm((int)$matches[1], (int)$matches[2]);
    });
    $router->post('/affectations/delete/(\d+)/(\d+)', function($matches) use ($affectationController) { // suppression
        $affectationController->delete((int)$matches[1], (int)$matches[2]);
    });
};