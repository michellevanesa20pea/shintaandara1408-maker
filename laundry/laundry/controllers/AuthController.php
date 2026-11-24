<?php
class AuthController extends Controller {
    function login() {
        if ($this->isPost()) {
            $u = User::byEmail(trim($_POST['email'] ?? ''));
            if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
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
        if ($this->isPost()) {
            $n = trim($_POST['name'] ?? ''); $e = trim($_POST['email'] ?? ''); $p = $_POST['password'] ?? '';
            if ($n === '' || !filter_var($e, FILTER_VALIDATE_EMAIL) || strlen($p) < 6) {
                $this->flash('danger', 'Isi semua data dengan benar (password min. 6 karakter).');
                $this->redirect('auth/register');
            }
            if (User::byEmail($e)) { $this->flash('danger', 'Email sudah terdaftar.'); $this->redirect('auth/register'); }
            User::create($n, $e, $p);
            $this->flash('success', 'Registrasi berhasil, silakan login.');
            $this->redirect('auth/login');
        }
        $this->view('auth/register');
    }
    function logout() {
        session_destroy();
        session_start();
        $this->flash('success', 'Anda berhasil logout.');
        $this->redirect('auth/login');
    }
}
