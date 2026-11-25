<?php
function url($p) { return BASE_URL . '?r=' . $p; }
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function rp($n) { return 'Rp ' . number_format((float)$n, 0, ',', '.'); }
function tgl($d) { return $d ? date('d M Y', strtotime($d)) : '-'; }
function validDate($s) { $d = DateTime::createFromFormat('Y-m-d', (string)$s); return $d && $d->format('Y-m-d') === $s; }
function csrf_field() {
    $_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(16));
    return '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
}

class Controller {
    protected function view($v, $d = []) {
        extract($d);
        require ROOT . '/views/layout/header.php';
        require ROOT . "/views/$v.php";
        require ROOT . '/views/layout/footer.php';
    }
    protected function redirect($to) { header('Location: ' . url($to)); exit; }
    protected function flash($type, $msg) { $_SESSION['flash'] = [$type, $msg]; }
    protected function isPost() { return $_SERVER['REQUEST_METHOD'] === 'POST'; }

    // Wajib login & sesuai role. Akun yang dinonaktifkan Admin langsung ditendang.
    protected function auth($role) {
        if (empty($_SESSION['user'])) {
            $this->flash('warning', 'Silakan login terlebih dahulu.');
            $this->redirect('auth/login');
        }
        if ($_SESSION['user']['role'] !== $role) {
            $this->flash('danger', 'Akses ditolak: halaman khusus ' . $role . '.');
            $this->redirect($_SESSION['user']['role'] . '/dashboard');
        }
        $u = User::find($_SESSION['user']['id']);
        if (!$u || $u['status'] !== 'Aktif') {
            unset($_SESSION['user']);
            $this->flash('danger', 'Akun tidak aktif atau sudah dihapus.');
            $this->redirect('auth/login');
        }
    }
}
