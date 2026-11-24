<?php
class UserController extends Controller {
    function __construct() { $this->auth('user'); }

    function dashboard() {
        $this->view('user/dashboard', ['services' => Service::all(), 'orders' => Order::forUser($_SESSION['user']['id'])]);
    }
    function create() { $this->view('user/form', ['services' => Service::all(), 'order' => null]); }

    function store() {
        if (!$this->isPost()) $this->redirect('user/dashboard');
        $d = $this->validated('user/create');
        Order::create($_SESSION['user']['id'], $d['sid'], $d['w'], $d['total'], $d['date'], $d['notes']);
        $this->flash('success', 'Pesanan berhasil dibuat. Menunggu diproses admin.');
        $this->redirect('user/dashboard');
    }
    function edit() {
        $o = Order::findForUser((int)($_GET['id'] ?? 0), $_SESSION['user']['id']);
        if (!$o || $o['status'] !== 'Menunggu') {
            $this->flash('danger', 'Pesanan tidak bisa diubah (sudah diproses/tidak ditemukan).');
            $this->redirect('user/dashboard');
        }
        $this->view('user/form', ['services' => Service::all(), 'order' => $o]);
    }
    function update() {
        if (!$this->isPost()) $this->redirect('user/dashboard');
        $id = (int)$_POST['id'];
        $d = $this->validated('user/edit&id=' . $id);
        $n = Order::updateByUser($id, $_SESSION['user']['id'], $d['sid'], $d['w'], $d['total'], $d['date'], $d['notes']);
        $this->flash($n ? 'success' : 'danger', $n ? 'Pesanan diperbarui.' : 'Pesanan tidak bisa diubah.');
        $this->redirect('user/dashboard');
    }
    function delete() {
        if ($this->isPost()) {
            $n = Order::deleteByUser((int)$_POST['id'], $_SESSION['user']['id']);
            $this->flash($n ? 'success' : 'danger', $n ? 'Pesanan dibatalkan.' : 'Pesanan yang sudah diproses tidak bisa dibatalkan.');
        }
        $this->redirect('user/dashboard');
    }

    // Validasi + hitung total di server (harga diambil dari data layanan milik Admin)
    private function validated($back) {
        $s = Service::find((int)($_POST['service_id'] ?? 0));
        $w = (float)($_POST['weight'] ?? 0);
        $date = $_POST['pickup_date'] ?? '';
        if (!$s || $w <= 0 || $w > 100 || !strtotime($date)) {
            $this->flash('danger', 'Data pesanan tidak valid.');
            $this->redirect($back);
        }
        return ['sid' => $s['id'], 'w' => $w, 'total' => (int)round($w * $s['price_per_kg']),
                'date' => $date, 'notes' => trim($_POST['notes'] ?? '')];
    }
}
