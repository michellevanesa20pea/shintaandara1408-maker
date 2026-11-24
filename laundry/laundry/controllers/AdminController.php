<?php
class AdminController extends Controller {
    function __construct() { $this->auth('admin'); }

    function dashboard() {
        $this->view('admin/dashboard', ['s' => Order::stats(), 'users' => User::count(), 'recent' => array_slice(Order::all(), 0, 5)]);
    }

    // CRUD Layanan (Admin) -> tampil di halaman User
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

    // Kelola pesanan masuk dari User
    function orders() { $this->view('admin/orders', ['orders' => Order::all()]); }
    function orderStatus() {
        $ok = ['Menunggu', 'Diproses', 'Selesai', 'Diambil'];
        if ($this->isPost() && in_array($_POST['status'] ?? '', $ok, true)) {
            Order::setStatus((int)$_POST['id'], $_POST['status']);
            $this->flash('success', 'Status pesanan #' . (int)$_POST['id'] . ' diubah menjadi ' . $_POST['status'] . '.');
        } else $this->flash('danger', 'Gagal mengubah status.');
        $this->redirect('admin/orders');
    }
    function orderDelete() {
        if ($this->isPost()) { Order::delete((int)$_POST['id']); $this->flash('success', 'Pesanan dihapus.'); }
        $this->redirect('admin/orders');
    }
}
