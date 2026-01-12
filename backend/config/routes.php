<?php

use Controller\AppController;
use Core\Request;
use Core\Response;
use Core\Router;

return function (Router $router, AppController $controller) {
    $router->get('/', [$controller, 'home']);
    $router->get('/home', [$controller, 'home']);
    //$router->get('/client', [$controller, 'client']);
    //$router->get('', [$controller, '']);
//    $router->get('', [$controller, '']);
//    $router->post('', [$controller, '']);
//    $router->getRegex('#^/games/(\d+)$#', function (Request $req, Response $res, array $m) use ($controller) {
//        $controller->gameById((int)$m[1]);
//    });
};