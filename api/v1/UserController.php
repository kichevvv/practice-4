<?php
require_once 'User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        $input = json_decode(file_get_contents('php://input'), true);
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Все поля обязательны']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Некорректный email']);
            return;
        }

        if ($this->userModel->findByEmail($email)) {
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'Пользователь с таким email уже существует']);
            return;
        }

        $user = [
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ];

        $newUser = $this->userModel->create($user);
        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Пользователь зарегистрирован', 'user' => $newUser]);
    }

    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if ($email === '' || $password === '') {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Email и пароль обязательны']);
            return;
        }

        $user = $this->userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Неверный email или пароль']);
            return;
        }

        echo json_encode(['status' => 'success', 'message' => 'Вход выполнен', 'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ]]);
    }

    public function index() {
        $users = $this->userModel->all();
        $result = array_map(function ($user) {
            return [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];
        }, $users);
        echo json_encode($result);
    }

    public function show($id) {
        $user = $this->userModel->find($id);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
            return;
        }
        echo json_encode([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ]);
    }

    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        $user = $this->userModel->find($id);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
            return;
        }

        $newPassword = $input['password'] ?? '';
        if ($newPassword === '') {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Пароль обязателен']);
            return;
        }

        $user['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userModel->update($id, $user);

        echo json_encode(['status' => 'success', 'message' => 'Пароль обновлён']);
    }

    public function delete($id) {
        if (!$this->userModel->find($id)) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Пользователь не найден']);
            return;
        }
        $this->userModel->delete($id);
        echo json_encode(['status' => 'success', 'message' => 'Пользователь удалён']);
    }
}