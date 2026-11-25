<?php
class Service {
    static function all() { return Database::q('SELECT * FROM services ORDER BY name')->fetchAll(); }
    static function find($id) { return Database::q('SELECT * FROM services WHERE id=?', [$id])->fetch(); }
    static function create($n, $p, $d) { Database::q('INSERT INTO services(name,price_per_kg,description) VALUES(?,?,?)', [$n, $p, $d]); }
    static function update($id, $n, $p, $d) { Database::q('UPDATE services SET name=?,price_per_kg=?,description=? WHERE id=?', [$n, $p, $d, $id]); }
    static function delete($id) { Database::q('DELETE FROM services WHERE id=?', [$id]); }
}
