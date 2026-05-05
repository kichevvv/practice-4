<?php
class User {
    private $filePath;

    public function __construct() {
        $this->filePath = __DIR__ . '/users.json';
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    private function readData() {
        $json = file_get_contents($this->filePath);
        return json_decode($json, true) ?: [];
    }

    private function writeData($data) {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function all() {
        return $this->readData();
    }

    public function find($id) {
        foreach ($this->readData() as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }

    public function findByEmail($email) {
        foreach ($this->readData() as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }

    public function create($data) {
        $users = $this->readData();
        $maxId = 0;
        foreach ($users as $u) {
            if ($u['id'] > $maxId) $maxId = $u['id'];
        }
        $data['id'] = $maxId + 1;
        $users[] = $data;
        $this->writeData($users);
        return $data;
    }

    public function update($id, $newData) {
        $users = $this->readData();
        foreach ($users as &$user) {
            if ($user['id'] === $id) {
                $user = array_merge($user, $newData);
                break;
            }
        }
        $this->writeData($users);
    }

    public function delete($id) {
        $users = $this->readData();
        $users = array_values(array_filter($users, function ($u) use ($id) {
            return $u['id'] !== $id;
        }));
        $this->writeData($users);
    }
}