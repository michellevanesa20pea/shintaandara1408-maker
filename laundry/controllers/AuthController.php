<?php
class AuthController extends Controller {
    private function guest() { if (!empty($_SESSION['user'])) $this->redirect($_SESSION['user']['role'] . '/dashboard'); }

    function login() {
        $this->guest();
        if ($this->isPost()) {
            $u = User::byEmail(trim($_POST['email'] ?? ''));
            if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
                if ($u['status'] !== 'Aktif') { $this->flash('danger', 'Akun Anda dinonaktifkan. Hubungi admin.'); $this->redirect('auth/login'); }
                session_regenerate_id(true);
                $_SESSION['user'] = ['id' => $u['id'], 'name' => $u['name'], 'role' => $u['role']];
                $this->flash('success', 'Selamat datang, ' . $u['name'] . '!');
                $this->redirect($u['role'] . '/dashboard');
            }
            $this->flash('danger', 'Email atau password salah.');
            $this->redirect('auth/login');
        }
        $this->view('auth/login');
    }
    function register() {
        $this->guest();
        if ($this->isPost()) {
            $n = trim($_POST['name'] ?? ''); $e = trim($_POST['email'] ?? ''); $p = $_POST['password'] ?? '';
            $ph = trim($_POST['phone'] ?? ''); $ad = trim($_POST['address'] ?? '');
            if ($n === '' || !filter_var($e, FILTER_VALIDATE_EMAIL) || strlen($p) < 6 ||
                !preg_match('/^[0-9+\- ]{8,20}$/', $ph) || $ad === '') {
                $this->flash('danger', 'Isi semua data dengan benar (password min. 6 karakter, no. telp 8-20 digit).');
                $this->redirect('auth/register');
            }
            if (User::byEmail($e)) { $this->flash('danger', 'Email sudah terdaftar.'); $this->redirect('auth/register'); }
            User::create($n, $e, $p, $ph, mb_substr($ad, 0, 255));
            $this->flash('success', 'Registrasi berhasil, silakan login.');
            $this->redirect('auth/login');
        }
        $this->view('auth/register');
    }
    function logout() {
        $_SESSION = [];
        session_regenerate_id(true);
        $this->flash('success', 'Anda berhasil logout.');
        $this->redirect('auth/login');
    }
}
