<?php

require_once "controllers/UsersController";

$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$controllers = [
    'users' => new UsersController(),
];

$routes = [
    '' => function () { header("Location: /users"); exit(); },
    'users' => ['users', 'index'],
    'users/create' => ['users', 'create'],
    'users/store' => ['users', 'store'],
];

require_once 'config/routes.php';
?>