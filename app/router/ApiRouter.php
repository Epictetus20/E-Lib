<?php
// Include all necessary controllers
require_once('../controllers/BookController.php');
require_once('../controllers/UserController.php');
require_once('../includes/ResponseHandler.php');

class ApiRouter {
    private $routes = [];

    public function __construct() {
        $this->defineRequests();
    }

    private function defineRequests() {
        $this->routes = [
            ['method' => 'GET', 'path' => '/api/book', 'handler' => [new BookController(), 'listBooks']],
            ['method' => 'GET', 'path' => '/api/book/(\d+)', 'handler' => [new BookController(), 'viewBook']],
            ['method' => 'GET', 'path' => '/api/add-book', 'handler' => [new BookController(), 'addBookForm']],
            ['method' => 'POST', 'path' => '/api/add-book', 'handler' => [new BookController(), 'addBook']],
            ['method' => 'PUT', 'path' => '/api/book/(\d+)', 'handler' => [new BookController(), 'updateBook']],
            ['method' => 'GET', 'path' => '/api/search/(\w+)', 'handler' => [new BookController(), 'searchBooks']],
            ['method' => 'POST', 'path' => '/api/login', 'handler' => [new UserController(), 'handleLogin']],
            ['method' => 'GET', 'path' => '/api/logout', 'handler' => [new UserController(), 'handleLogout']],
        ];
    }

    public function handleRequest($method, $path) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match("#^{$route['path']}$#", $path, $matches)) {
                array_shift($matches); // Remove the full match from the matches array
                call_user_func_array($route['handler'], $matches);
                return;
            }
        }
        ResponseHandler::respond(404, 'Not Found');
    }
}
