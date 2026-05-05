<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'UserController.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$controller = new UserController();

if (preg_match('#^/api/v1/register$#', $requestUri) && $method === 'POST') {
    $controller->register();
} elseif (preg_match('#^/api/v1/login$#', $requestUri) && $method === 'POST') {
    $controller->login();
} elseif (preg_match('#^/api/v1/users$#', $requestUri) && $method === 'GET') {
    $controller->index();
} elseif (preg_match('#^/api/v1/users/(\d+)$#', $requestUri, $matches) && $method === 'GET') {
    $controller->show((int)$matches[1]);
} elseif (preg_match('#^/api/v1/users/(\d+)$#', $requestUri, $matches) && $method === 'PUT') {
    $controller->update((int)$matches[1]);
} elseif (preg_match('#^/api/v1/users/(\d+)$#', $requestUri, $matches) && $method === 'DELETE') {
    $controller->delete((int)$matches[1]);
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Маршрут не найден']);
}