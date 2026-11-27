<?php
class AuthController extends Controller {
    function login() {
        $this->post(); $b = $this->body();
        $peran = ($b['peran'] ?? '') === 'admin' ? 'admin' : 'pelanggan';
        $a = Akun::byNama(trim($b['nama'] ?? ''), $peran);
        if (!$a || !password_verify((string)($b['sandi'] ?? ''), $a['sandi'])) $this->fail('akun atau password salah', 401);
        if ($a['status'] !== 'Aktif') $this->fail('akun Anda dinonaktifkan', 403);
        session_regenerate_id(true);
        $_SESSION['uid'] = (int)$a['id'];
        $this->ok(['user' => Akun::publik($a)]);
    }
    // Hanya pelanggan yang boleh mendaftar sendiri. Akun admin dibuat lewat database.
    function register() {
        $this->post(); $b = $this->body();
        $nama = trim($b['nama'] ?? ''); $telp = trim($b['telp'] ?? ''); $alamat = trim($b['alamat'] ?? ''); $sandi = (string)($b['sandi'] ?? '');
        if ($nama === '' || mb_strlen($nama) > 100) $this->fail('nama wajib diisi (maks. 100 karakter)');
        if (!preg_match('/^[0-9+\- ]{8,20}$/', $telp)) $this->fail('no. telepon tidak valid (8-20 digit)');
        if ($alamat === '') $this->fail('alamat wajib diisi');
        if (strlen($sandi) < 6) $this->fail('password minimal 6 karakter');
        if (Akun::namaAda($nama)) $this->fail('nama sudah terdaftar');
        Akun::buat($nama, $telp, mb_substr($alamat, 0, 255), $sandi);
        $this->ok();
    }
    function logout() { $this->post(); $_SESSION = []; session_regenerate_id(true); $this->ok(); }
    function me() {
        $u = isset($_SESSION['uid']) ? Akun::find((int)$_SESSION['uid']) : null;
        $this->ok(['user' => ($u && $u['status'] === 'Aktif') ? Akun::publik($u) : null]);
    }
}
