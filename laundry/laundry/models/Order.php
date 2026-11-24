<?php
class Order {
    const BASE = 'SELECT o.*, s.name service_name, u.name customer FROM orders o
                  JOIN services s ON s.id=o.service_id JOIN users u ON u.id=o.user_id ';
    static function all() { return Database::q(self::BASE . 'ORDER BY o.id DESC')->fetchAll(); }
    static function forUser($uid) { return Database::q(self::BASE . 'WHERE o.user_id=? ORDER BY o.id DESC', [$uid])->fetchAll(); }
    static function findForUser($id, $uid) { return Database::q('SELECT * FROM orders WHERE id=? AND user_id=?', [$id, $uid])->fetch(); }
    static function create($uid, $sid, $w, $total, $date, $notes) {
        Database::q('INSERT INTO orders(user_id,service_id,weight,total,pickup_date,notes) VALUES(?,?,?,?,?,?)',
            [$uid, $sid, $w, $total, $date, $notes]);
    }
    // User hanya boleh ubah/hapus pesanannya sendiri yang masih "Menunggu"
    static function updateByUser($id, $uid, $sid, $w, $total, $date, $notes) {
        return Database::q('UPDATE orders SET service_id=?,weight=?,total=?,pickup_date=?,notes=? WHERE id=? AND user_id=? AND status="Menunggu"',
            [$sid, $w, $total, $date, $notes, $id, $uid])->rowCount();
    }
    static function deleteByUser($id, $uid) {
        return Database::q('DELETE FROM orders WHERE id=? AND user_id=? AND status="Menunggu"', [$id, $uid])->rowCount();
    }
    static function setStatus($id, $st) { Database::q('UPDATE orders SET status=? WHERE id=?', [$st, $id]); }
    static function delete($id) { Database::q('DELETE FROM orders WHERE id=?', [$id]); }
    static function stats() {
        return Database::q('SELECT COUNT(*) total, SUM(status="Menunggu") menunggu, SUM(status="Diproses") diproses,
            SUM(status IN("Selesai","Diambil")) selesai, COALESCE(SUM(total),0) omzet FROM orders')->fetch();
    }
}
