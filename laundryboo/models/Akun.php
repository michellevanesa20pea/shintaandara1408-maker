<?php
class Akun {
    static function publik($a) {
        return ['id' => (int)$a['id'], 'nama' => $a['nama'], 'telp' => $a['telp'], 'alamat' => $a['alamat'],
                'peran' => $a['peran'], 'status' => $a['status']];
    }
    static function find($id) { return Database::q('SELECT * FROM akun WHERE id=?', [$id])->fetch(); }
    static function byNama($n, $peran) { return Database::q('SELECT * FROM akun WHERE nama=? AND peran=?', [$n, $peran])->fetch(); }
    static function namaAda($n, $kecuali = 0) { return (bool)Database::q('SELECT 1 FROM akun WHERE nama=? AND id<>?', [$n, $kecuali])->fetch(); }
    static function buat($n, $telp, $alamat, $sandi) {
        Database::q('INSERT INTO akun(nama,telp,alamat,sandi,peran) VALUES(?,?,?,?,"pelanggan")', [$n, $telp, $alamat, password_hash($sandi, PASSWORD_DEFAULT)]);
    }
    static function pelanggan() {
        return array_map([self::class, 'publik'], Database::q('SELECT * FROM akun WHERE peran="pelanggan" ORDER BY id DESC')->fetchAll());
    }
    static function ubah($id, $nama, $sandi = '') {
        if ($sandi !== '') Database::q('UPDATE akun SET nama=?, sandi=? WHERE id=? AND peran="pelanggan"', [$nama, password_hash($sandi, PASSWORD_DEFAULT), $id]);
        else Database::q('UPDATE akun SET nama=? WHERE id=? AND peran="pelanggan"', [$nama, $id]);
    }
    static function ubahKontak($id, $telp, $alamat) { Database::q('UPDATE akun SET telp=?, alamat=? WHERE id=?', [$telp, $alamat, $id]); }
    static function gantiStatus($id) { Database::q('UPDATE akun SET status=IF(status="Aktif","Nonaktif","Aktif") WHERE id=? AND peran="pelanggan"', [$id]); }
    static function hapus($id) { Database::q('DELETE FROM akun WHERE id=? AND peran="pelanggan"', [$id]); }
}
