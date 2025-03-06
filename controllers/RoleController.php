<?php
require_once 'models/Role.php';

class RoleController {
    public function index() {
        $data = Role::getAll();
        require 'views/Role/index.php';
    }

    public function create() {
        require 'views/Role/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Role::insert($_POST);
            header("Location: /Role");
            exit();
        }
    }

    public function edit($id) {
        $data = Role::getById($id);
        require 'views/Role/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Role::update($id, $_POST);
            header("Location: /Role");
            exit();
        }
    }

    public function delete($id) {
        Role::delete($id);
        header("Location: /Role");
        exit();
    }
}