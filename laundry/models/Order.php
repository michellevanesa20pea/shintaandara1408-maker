<?php
class Order {
    const BASE = 'SELECT o.*, s.name service_name, u.name customer, u.phone phone FROM orders o
                  JOIN services s ON s.id=o.service_id JOIN users u ON u.id=o.user_id ';
    static function all($q = '') { return Database::q(self::BASE . 'WHERE u.name LIKE ? ORDER BY o.id DESC', ["%$q%"])->fetchAll(); }
    static function forUser($uid) { return Database::q(self::BASE . 'WHERE o.user_id=? ORDER BY o.id DESC', [$uid])->fetchAll(); }
    static function find($id) { return Database::q(self::BASE . 'WHERE o.id=?', [$id])->fetch(); }
    static function create($uid, $d) {
        Database::q('INSERT INTO orders(user_id,service_id,weight,price_per_kg,total,entry_date,finish_date,payment_method,notes)
                     VALUES(?,?,?,?,?,?,?,?,?)',
            [$uid, $d['sid'], $d['w'], $d['price'], $d['total'], $d['in'], $d['out'], $d['pay'], $d['notes']]);
    }
    // User hanya boleh ubah/batalkan pesanannya sendiri yang masih "Menunggu"
    static function updateByUser($id, $uid, $d) {
        return Database::q('UPDATE orders SET service_id=?,weight=?,price_per_kg=?,total=?,entry_date=?,finish_date=?,payment_method=?,notes=?
                            WHERE id=? AND user_id=? AND status="Menunggu"',
            [$d['sid'], $d['w'], $d['price'], $d['total'], $d['in'], $d['out'], $d['pay'], $d['notes'], $id, $uid])->rowCount();
    }
    static function deleteByUser($id, $uid) {
        return Database::q('DELETE FROM orders WHERE id=? AND user_id=? AND status="Menunggu"', [$id, $uid])->rowCount();
    }
    static function setStatus($id, $st) {
        Database::q('UPDATE orders SET status=?, paid=IF(?="Diambil",1,paid) WHERE id=?', [$st, $st, $id]);
    }
    static function togglePaid($id) { Database::q('UPDATE orders SET paid=1-paid WHERE id=?', [$id]); }
    static function delete($id) { Database::q('DELETE FROM orders WHERE id=?', [$id]); }
    static function stats() {
        return Database::q('SELECT COUNT(*) total, COALESCE(SUM(status="Menunggu"),0) menunggu,
            COALESCE(SUM(status="Diproses"),0) diproses, COALESCE(SUM(status IN("Selesai","Diambil")),0) selesai,
            COALESCE(SUM(CASE WHEN paid=1 THEN total END),0) omzet FROM orders')->fetch();
    }
}
