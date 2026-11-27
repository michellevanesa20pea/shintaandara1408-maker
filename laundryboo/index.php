<?php
// FRONT CONTROLLER: index.php            -> halaman web (app/index)
//                   index.php?r=c/m      -> endpoint JSON (controller c, method m)
define('ROOT', __DIR__);
require ROOT . '/config/config.php';
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();
require ROOT . '/core/Database.php';
require ROOT . '/core/Controller.php';
foreach (glob(ROOT . '/models/*.php') as $f) require $f;

set_exception_handler(function ($e) {
    http_response_code(500); header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'pesan' => DEBUG ? $e->getMessage() : 'Terjadi kesalahan pada server'], JSON_UNESCAPED_UNICODE);
});

$r = trim($_GET['r'] ?? 'app/index', '/');
[$c, $m] = array_pad(explode('/', $r), 2, 'index');
$file = ROOT . '/controllers/' . ucfirst($c) . 'Controller.php';
if (!preg_match('/^[a-zA-Z]+$/', $c . $m) || !is_file($file)) { http_response_code(404); die('404'); }
require $file;
$class = ucfirst($c) . 'Controller';
$ctrl = new $class;
if (!is_callable([$ctrl, $m])) { http_response_code(404); die('404'); }   // method protected/private ditolak
$ctrl->$m();
