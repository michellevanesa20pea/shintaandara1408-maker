<?php
// FRONT CONTROLLER / ROUTER: index.php?r=controller/method
session_start();
require 'config/config.php';
require 'core/Database.php';
require 'core/Controller.php';
foreach (glob('models/*.php') as $f) require $f;

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    http_response_code(419); die('Token tidak valid.');
}

$r = trim($_GET['r'] ?? 'auth/login', '/');
[$c, $m] = array_pad(explode('/', $r), 2, 'index');
if (!preg_match('/^[a-zA-Z]+$/', $c . $m)) { http_response_code(404); die('404'); }
$class = ucfirst($c) . 'Controller';
$file = "controllers/$class.php";
if (!is_file($file)) { http_response_code(404); die('404 Halaman tidak ditemukan'); }
require $file;
$ctrl = new $class;
if (!method_exists($ctrl, $m)) { http_response_code(404); die('404 Halaman tidak ditemukan'); }
$ctrl->$m();
