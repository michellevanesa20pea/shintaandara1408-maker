<?php
// Kelola akun pelanggan (khusus admin)
class AkunController extends Controller {
    function daftar() { $this->auth('admin'); $this->ok(Akun::pelanggan()); }
    function ubah() {
        $this->post(); $this->auth('admin'); $b = $this->body();
        $id = (int)($b['id'] ?? 0); $nama = trim($b['nama'] ?? ''); $sandi = (string)($b['sandi'] ?? '');
        $a = Akun::find($id);
        if (!$a || $a['peran'] !== 'pelanggan') $this->fail('akun tidak ditemukan', 404);
        if ($nama === '' || mb_strlen($nama) > 100) $this->fail('nama wajib diisi');
        if (Akun::namaAda($nama, $id)) $this->fail('nama sudah digunakan');
        if ($sandi !== '' && strlen($sandi) < 6) $this->fail('password baru minimal 6 karakter');
        Akun::ubah($id, $nama, $sandi);
        $this->ok();
    }
    function status() { $this->post(); $this->auth('admin'); Akun::gantiStatus((int)($this->body()['id'] ?? 0)); $this->ok(); }
    function hapus()  { $this->post(); $this->auth('admin'); Akun::hapus((int)($this->body()['id'] ?? 0)); $this->ok(); }
}
