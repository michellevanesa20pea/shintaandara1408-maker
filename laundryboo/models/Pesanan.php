<?php
class Pesanan {
    // Nama kolom di-alias supaya bentuknya sama dengan objek yang dipakai laundry.js
    const BASE = 'SELECT p.id, p.akun_id idAkun, a.nama, p.telp, p.alamat, p.tgl_masuk tglMasuk, p.tgl_selesai tglSelesai,
        l.nama layanan, p.layanan_id layananId, p.berat, p.harga_kg hargaKg, p.total, p.metode, p.sudah_bayar sudahBayar, p.status
        FROM pesanan p JOIN akun a ON a.id=p.akun_id JOIN layanan l ON l.id=p.layanan_id ';
    private static function cast($p) {
        foreach (['id', 'idAkun', 'layananId', 'hargaKg', 'total'] as $k) $p[$k] = (int)$p[$k];
        $p['berat'] = (float)$p['berat']; $p['sudahBayar'] = (bool)$p['sudahBayar'];
        return $p;
    }
    static function semua() { return array_map([self::class, 'cast'], Database::q(self::BASE . 'ORDER BY p.id DESC')->fetchAll()); }
    static function milik($uid) { return array_map([self::class, 'cast'], Database::q(self::BASE . 'WHERE p.akun_id=? ORDER BY p.id DESC', [$uid])->fetchAll()); }
    static function cari($id) { $p = Database::q(self::BASE . 'WHERE p.id=?', [$id])->fetch(); return $p ? self::cast($p) : null; }
    static function buat($uid, $d) {
        Database::q('INSERT INTO pesanan(akun_id,layanan_id,berat,harga_kg,total,telp,alamat,tgl_masuk,tgl_selesai,metode) VALUES(?,?,?,?,?,?,?,?,?,?)',
            [$uid, $d['layananId'], $d['berat'], $d['harga'], $d['total'], $d['telp'], $d['alamat'], $d['tglMasuk'], $d['tglSelesai'], $d['metode']]);
    }
    static function ubah($id, $d) {
        Database::q('UPDATE pesanan SET layanan_id=?, berat=?, harga_kg=?, total=?, tgl_selesai=?, status=?, sudah_bayar=? WHERE id=?',
            [$d['layananId'], $d['berat'], $d['harga'], $d['total'], $d['tglSelesai'], $d['status'], $d['bayar'] ? 1 : 0, $id]);
    }
    static function hapus($id) { Database::q('DELETE FROM pesanan WHERE id=?', [$id]); }
}
