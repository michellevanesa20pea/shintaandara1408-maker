<?php
class PesananController extends Controller {
    const STATUS = ['Menunggu', 'Diproses', 'Selesai', 'Diambil'];

    // Admin melihat semua pesanan, pelanggan hanya miliknya sendiri
    function daftar() {
        $u = $this->auth();
        $this->ok($u['peran'] === 'admin' ? Pesanan::semua() : Pesanan::milik((int)$u['id']));
    }
    // Pelanggan membuat pesanan -> langsung masuk ke dashboard admin. Total dihitung server.
    function buat() {
        $this->post(); $u = $this->auth('pelanggan'); $b = $this->body();
        $lay = Layanan::cari((int)($b['layananId'] ?? 0));
        $berat = round((float)($b['berat'] ?? 0), 1);
        $in = $b['tglMasuk'] ?? ''; $out = $b['tglSelesai'] ?? '';
        $telp = trim($b['telp'] ?? ''); $alamat = trim($b['alamat'] ?? '');
        if (!$lay) $this->fail('layanan tidak ditemukan');
        if ($berat <= 0 || $berat > 100) $this->fail('berat harus 0.1 - 100 kg');
        if (!validTanggal($in) || !validTanggal($out)) $this->fail('tanggal tidak valid');
        if ($out < $in) $this->fail('tanggal selesai lebih awal dari tanggal masuk');
        if (!in_array($b['metode'] ?? '', ['Cash', 'QRIS'], true)) $this->fail('pilih metode pembayaran');
        if (!preg_match('/^[0-9+\- ]{8,20}$/', $telp) || $alamat === '') $this->fail('no. telepon dan alamat wajib diisi dengan benar');
        $alamat = mb_substr($alamat, 0, 255);
        Pesanan::buat((int)$u['id'], ['layananId' => (int)$lay['id'], 'berat' => $berat, 'harga' => (int)$lay['harga'],
            'total' => (int)round($berat * $lay['harga']), 'telp' => $telp, 'alamat' => $alamat,
            'tglMasuk' => $in, 'tglSelesai' => $out, 'metode' => $b['metode']]);
        Akun::ubahKontak((int)$u['id'], $telp, $alamat);
        $this->ok();
    }
    // Admin mengubah status/pembayaran/detail pesanan -> terlihat di halaman pelanggan
    function ubah() {
        $this->post(); $this->auth('admin'); $b = $this->body();
        $p = Pesanan::cari((int)($b['id'] ?? 0));
        $lay = Layanan::cari((int)($b['layananId'] ?? 0));
        $berat = round((float)($b['berat'] ?? 0), 1);
        $out = $b['tglSelesai'] ?? ''; $status = $b['status'] ?? '';
        if (!$p || !$lay) $this->fail('data pesanan/layanan tidak ditemukan', 404);
        if ($berat <= 0 || $berat > 100) $this->fail('berat harus 0.1 - 100 kg');
        if (!validTanggal($out) || $out < $p['tglMasuk']) $this->fail('tanggal selesai tidak valid');
        if (!in_array($status, self::STATUS, true)) $this->fail('status tidak valid');
        $harga = ((int)$lay['id'] === $p['layananId']) ? $p['hargaKg'] : (int)$lay['harga'];   // harga lama dipertahankan jika layanan sama
        Pesanan::ubah($p['id'], ['layananId' => (int)$lay['id'], 'berat' => $berat, 'harga' => $harga,
            'total' => (int)round($berat * $harga), 'tglSelesai' => $out, 'status' => $status,
            'bayar' => !empty($b['sudahBayar']) || $status === 'Diambil']);
        $this->ok();
    }
    function hapus() { $this->post(); $this->auth('admin'); Pesanan::hapus((int)($this->body()['id'] ?? 0)); $this->ok(); }
}
