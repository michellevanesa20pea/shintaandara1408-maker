<?php
function url($p) { return BASE_URL . '?r=' . $p; }
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function rp($n) { return 'Rp ' . number_format($n, 0, ',', '.'); }
function csrf_field() {
    $_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(16));
    return '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
}

class Controller {
    protected function view($v, $d = []) {
        extract($d);
        require 'views/layout/header.php';
        require "views/$v.php";
        require 'views/layout/footer.php';
    }
    protected function redirect($to) { header('Location: ' . url($to)); exit; }
    protected function flash($type, $msg) { $_SESSION['flash'] = [$type, $msg]; }
    protected function isPost() { return $_SERVER['REQUEST_METHOD'] === 'POST'; }

    // Proteksi halaman: wajib login & sesuai role (admin tidak bisa dibuka lewat URL oleh User)
    protected function auth($role) {
        if (empty($_SESSION['user'])) {
            $this->flash('warning', 'Silakan login terlebih dahulu.');
            $this->redirect('auth/login');
        }
        if ($_SESSION['user']['role'] !== $role) {
            $this->flash('danger', 'Akses ditolak: halaman khusus ' . $role . '.');
            $this->redirect($_SESSION['user']['role'] . '/dashboard');
        }
    }
}
