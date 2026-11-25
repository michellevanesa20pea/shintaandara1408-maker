<?php
// FRONT CONTROLLER / ROUTER: index.php?r=controller/method
define('ROOT', __DIR__);
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();
require ROOT . '/config/config.php';
require ROOT . '/core/Database.php';
require ROOT . '/core/Controller.php';
foreach (glob(ROOT . '/models/*.php') as $f) require $f;

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    http_response_code(419); die('Sesi/token tidak valid. Muat ulang halaman lalu coba lagi.');
}

$r = trim($_GET['r'] ?? 'auth/login', '/');
[$c, $m] = array_pad(explode('/', $r), 2, 'index');
if (!preg_match('/^[a-zA-Z]+$/', $c . $m)) { http_response_code(404); die('404'); }
$class = ucfirst($c) . 'Controller';
$file = ROOT . "/controllers/$class.php";
if (!is_file($file)) { http_response_code(404); die('404 Halaman tidak ditemukan'); }
require $file;
$ctrl = new $class;
// is_callable dari luar class: method private/protected (view, redirect, validated, dst) otomatis ditolak
if (!is_callable([$ctrl, $m])) { http_response_code(404); die('404 Halaman tidak ditemukan'); }
$ctrl->$m();
