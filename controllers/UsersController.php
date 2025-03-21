<?php
require_once 'models/Users.php';

class UsersController {
    public function index() {
        $data = Users::getAll();
        require 'views/Users/index.php';
    }

    public function create() {
        require 'views/Users/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Users::insert($_POST);
            header("Location: /Users");
            exit();
        }
    }

    public function edit($id) {
        $data = Users::getById($id);
        require 'views/Users/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Users::update($id, $_POST);
            header("Location: /Users");
            exit();
        }
    }

    public function delete($id) {
        Users::delete($id);
        header("Location: /Users");
        exit();
    }
}