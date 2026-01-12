<?php

namespace Core;

final class Router {

    private array $getRoutes = [];
    private array $postRoutes = [];
    private array $getRegexRoutes = [];
    private array $postRegexRoutes = [];

    public function get(string $path, callable $handler): void {
        if (str_contains($path, '(')) {
            $this->getRegexRoutes[$path] = $handler;
        } else {
            $this->getRoutes[$path] = $handler;
        }
    }

    public function post(string $path, callable $handler) :void {
        if (str_contains($path, '(')) {
            $this->postRegexRoutes[$path] = $handler;
        } else {
            $this->postRoutes[$path] = $handler;
        }
    }

    public function getRegex(string $pattern, callable $handler) : void {
        $this->getRegexRoutes[$pattern] = $handler;
    }

    public function dispatch (Request $request, Response $response) : void {
        $path = $request->path();

        $method = $request->method();

        if ($method === 'GET' && isset($this->getRoutes[$path])) {
            $this->getRoutes[$path]($request, $response);
            return;
        }

        if ($method === 'POST' && isset($this->postRoutes[$path])) {
            $this->postRoutes[$path]($request, $response);
            return;
        }

        if ($method === 'GET') {
            foreach ($this->getRegexRoutes as $pattern => $handler) {
                // Transforme le pattern en regex valide
                $regex = '#^' . $pattern . '$#';
                if (preg_match($regex, $path, $matches)) {
                    $handler($matches);  // ← Passe les matches à la closure
                    return;
                }
            }
        }

        if ($method === 'POST') {
            foreach ($this->postRegexRoutes as $pattern => $handler) {
                $regex = '#^' . $pattern . '$#';
                if (preg_match($regex, $path, $matches)) {
                    $handler($matches);
                    return;
                }
            }
        }

        http_response_code(404);
        echo "Page non trouvée";


        /*$response->render('not-found', [], 404);*/
    }
}