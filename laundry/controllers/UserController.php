<?php
class UserController extends Controller {
    function __construct() { $this->auth('user'); }

    function dashboard() {
        $this->view('user/dashboard', ['orders' => Order::forUser($_SESSION['user']['id'])]);
    }
    function create() { $this->view('user/form', ['services' => Service::all(), 'order' => null]); }

    function store() {
        if (!$this->isPost()) $this->redirect('user/dashboard');
        Order::create($_SESSION['user']['id'], $this->validated('user/create'));
        $this->flash('success', 'Pesanan berhasil dibuat. Menunggu diproses admin.');
        $this->redirect('user/dashboard');
    }
    function edit() {
        $o = Order::find((int)($_GET['id'] ?? 0));
        if (!$o || $o['user_id'] != $_SESSION['user']['id'] || $o['status'] !== 'Menunggu') {
            $this->flash('danger', 'Pesanan tidak bisa diubah (sudah diproses/tidak ditemukan).');
            $this->redirect('user/dashboard');
        }
        $this->view('user/form', ['services' => Service::all(), 'order' => $o]);
    }
    function update() {
        if (!$this->isPost()) $this->redirect('user/dashboard');
        $id = (int)$_POST['id'];
        $n = Order::updateByUser($id, $_SESSION['user']['id'], $this->validated('user/edit&id=' . $id));
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
    function receipt() {
        $o = Order::find((int)($_GET['id'] ?? 0));
        if (!$o || $o['user_id'] != $_SESSION['user']['id']) { $this->flash('danger', 'Struk tidak ditemukan.'); $this->redirect('user/dashboard'); }
        $this->view('shared/receipt', ['o' => $o]);
    }

    // Validasi di server; harga diambil dari data layanan milik Admin lalu disimpan sebagai snapshot
    private function validated($back) {
        $s = Service::find((int)($_POST['service_id'] ?? 0));
        $w = round((float)($_POST['weight'] ?? 0), 1);
        $in = $_POST['entry_date'] ?? ''; $out = $_POST['finish_date'] ?? ''; $pay = $_POST['payment_method'] ?? '';
        if (!$s || $w <= 0 || $w > 100 || !validDate($in) || !validDate($out) || $out < $in || !in_array($pay, ['Cash', 'QRIS'], true)) {
            $this->flash('danger', 'Data pesanan tidak valid (cek berat, tanggal, dan metode bayar).');
            $this->redirect($back);
        }
        return ['sid' => $s['id'], 'w' => $w, 'price' => $s['price_per_kg'], 'total' => (int)round($w * $s['price_per_kg']),
                'in' => $in, 'out' => $out, 'pay' => $pay, 'notes' => mb_substr(trim($_POST['notes'] ?? ''), 0, 255)];
    }
}
