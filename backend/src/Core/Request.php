<?php

namespace App\Core;

class Request
{
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function path(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    }

    public function getMethod(): string
    {
        return $this->method();
    }

    public function getBody(): array
    {
        $method = $this->method();

        if ($method === 'POST') {
            return $_POST;
        }

        if (in_array($method, ['PUT', 'PATCH', 'DELETE'], true)) {
            parse_str(file_get_contents('php://input'), $data);
            return $data;
        }

        return [];
    }
}
