<?php
function validTanggal($s) { $d = DateTime::createFromFormat('Y-m-d', (string)$s); return $d && $d->format('Y-m-d') === $s; }

class Controller {
    protected function body() {
        static $b = null;
        if ($b === null) $b = json_decode(file_get_contents('php://input'), true) ?: [];
        return $b;
    }
    protected function ok($data = []) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE); exit;
    }
    protected function fail($pesan, $kode = 400) {
        http_response_code($kode); header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'pesan' => $pesan], JSON_UNESCAPED_UNICODE); exit;
    }
    // Aksi yang mengubah data wajib POST + header X-Requested-With (proteksi CSRF)
    protected function post() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->fail('Metode harus POST', 405);
        if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'fetch') $this->fail('Permintaan tidak valid', 403);
    }
    // Wajib login (+ sesuai peran). Status akun dicek ulang ke database tiap request.
    protected function auth($peran = null) {
        $u = isset($_SESSION['uid']) ? Akun::find((int)$_SESSION['uid']) : null;
        if (!$u) $this->fail('Silakan login terlebih dahulu', 401);
        if ($u['status'] !== 'Aktif') { $_SESSION = []; $this->fail('Akun Anda dinonaktifkan', 403); }
        if ($peran && $u['peran'] !== $peran) $this->fail('Akses ditolak: halaman khusus ' . $peran, 403);
        return $u;
    }
}
