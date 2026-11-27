<?php
class Layanan {
    static function semua() {
        return array_map(fn($l) => ['id' => (int)$l['id'], 'nama' => $l['nama'], 'harga' => (int)$l['harga']],
            Database::q('SELECT * FROM layanan ORDER BY id')->fetchAll());
    }
    static function cari($id) { return Database::q('SELECT * FROM layanan WHERE id=?', [$id])->fetch(); }
}
