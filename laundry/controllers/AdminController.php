<?php
class AdminController extends Controller {
    function __construct() { $this->auth('admin'); }

    function dashboard() {
        $this->view('admin/dashboard', ['s' => Order::stats(), 'users' => User::count(), 'recent' => array_slice(Order::all(), 0, 5)]);
    }

    // ---- CRUD Layanan ----
    function services() {
        $edit = isset($_GET['id']) ? Service::find((int)$_GET['id']) : null;
        $this->view('admin/services', ['services' => Service::all(), 'edit' => $edit]);
    }
    function serviceSave() {
        if (!$this->isPost()) $this->redirect('admin/services');
        $id = (int)($_POST['id'] ?? 0);
        $n = trim($_POST['name'] ?? ''); $p = (int)($_POST['price'] ?? 0); $d = trim($_POST['description'] ?? '');
        if ($n === '' || $p <= 0) { $this->flash('danger', 'Nama dan harga wajib diisi (harga > 0).'); $this->redirect('admin/services'); }
        if ($id) { Service::update($id, $n, $p, $d); $this->flash('success', 'Layanan diperbarui.'); }
        else     { Service::create($n, $p, $d);      $this->flash('success', 'Layanan ditambahkan.'); }
        $this->redirect('admin/services');
    }
    function serviceDelete() {
        if ($this->isPost()) {
            try { Service::delete((int)$_POST['id']); $this->flash('success', 'Layanan dihapus.'); }
            catch (PDOException $e) { $this->flash('danger', 'Gagal: layanan masih dipakai pada pesanan.'); }
        }
        $this->redirect('admin/services');
    }

    // ---- Kelola pesanan masuk ----
    function orders() { $q = trim($_GET['q'] ?? ''); $this->view('admin/orders', ['orders' => Order::all($q), 'q' => $q]); }
    function orderStatus() {
        if ($this->isPost() && in_array($_POST['status'] ?? '', ['Menunggu', 'Diproses', 'Selesai', 'Diambil'], true)) {
            Order::setStatus((int)$_POST['id'], $_POST['status']);
            $this->flash('success', 'Status pesanan #' . (int)$_POST['id'] . ' diubah menjadi ' . $_POST['status'] . '.');
        } else $this->flash('danger', 'Gagal mengubah status.');
        $this->redirect('admin/orders');
    }
    function orderPaid() {
        if ($this->isPost()) { Order::togglePaid((int)$_POST['id']); $this->flash('success', 'Status pembayaran diperbarui.'); }
        $this->redirect('admin/orders');
    }
    function orderDelete() {
        if ($this->isPost()) { Order::delete((int)$_POST['id']); $this->flash('success', 'Pesanan dihapus.'); }
        $this->redirect('admin/orders');
    }
    function receipt() {
        $o = Order::find((int)($_GET['id'] ?? 0));
        if (!$o) { $this->flash('danger', 'Struk tidak ditemukan.'); $this->redirect('admin/orders'); }
        $this->view('shared/receipt', ['o' => $o]);
    }

    // ---- Kelola akun pelanggan ----
    function customers() { $q = trim($_GET['q'] ?? ''); $this->view('admin/customers', ['rows' => User::customers($q), 'q' => $q]); }
    function customerEdit() {
        $u = User::find((int)($_GET['id'] ?? 0));
        if (!$u || $u['role'] !== 'user') { $this->flash('danger', 'Akun tidak ditemukan.'); $this->redirect('admin/customers'); }
        $this->view('admin/customer_form', ['u' => $u]);
    }
    function customerUpdate() {
        if (!$this->isPost()) $this->redirect('admin/customers');
        $id = (int)$_POST['id']; $n = trim($_POST['name'] ?? ''); $ph = trim($_POST['phone'] ?? '');
        $ad = trim($_POST['address'] ?? ''); $p = $_POST['password'] ?? '';
        if ($n === '' || !preg_match('/^[0-9+\- ]{8,20}$/', $ph) || ($p !== '' && (strlen($p) < 6 || $p !== ($_POST['password2'] ?? '')))) {
            $this->flash('danger', 'Data tidak valid (password baru min. 6 karakter dan harus sama dengan verifikasi).');
            $this->redirect('admin/customerEdit&id=' . $id);
        }
        User::update($id, $n, $ph, mb_substr($ad, 0, 255), $p);
        $this->flash('success', 'Data pelanggan diperbarui.');
        $this->redirect('admin/customers');
    }
    function customerToggle() {
        if ($this->isPost()) { User::toggle((int)$_POST['id']); $this->flash('success', 'Status akun diperbarui.'); }
        $this->redirect('admin/customers');
    }
    function customerDelete() {
        if ($this->isPost()) { User::delete((int)$_POST['id']); $this->flash('success', 'Akun pelanggan dan pesanannya dihapus.'); }
        $this->redirect('admin/customers');
    }
}
