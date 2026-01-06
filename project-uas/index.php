<?php
session_start();

$url = $_GET['url'] ?? 'auth';
$url = explode('/', rtrim($url,'/'));

$controllerName = ucfirst($url[0]) . 'Controller';
$method = $url[1] ?? 'index';

$controllerFile = "app/controllers/$controllerName.php";

if (!file_exists($controllerFile)) {
    die("Controller tidak ditemukan");
}

require_once 'config/database.php';
require_once $controllerFile;

$controller = new $controllerName;

if (!method_exists($controller, $method)) {
    die("Method tidak ditemukan");
}

$controller->$method();
