<?php
class User {
    static function byEmail($e) { return Database::q('SELECT * FROM users WHERE email=?', [$e])->fetch(); }
    static function find($id) { return Database::q('SELECT * FROM users WHERE id=?', [$id])->fetch(); }
    static function create($n, $e, $p, $phone, $addr) {
        Database::q('INSERT INTO users(name,email,password,phone,address,role) VALUES(?,?,?,?,?,"user")',
            [$n, $e, password_hash($p, PASSWORD_DEFAULT), $phone, $addr]);
    }
    static function customers($q = '') {
        return Database::q('SELECT u.*, (SELECT COUNT(*) FROM orders o WHERE o.user_id=u.id) total_orders
            FROM users u WHERE u.role="user" AND u.name LIKE ? ORDER BY u.id DESC', ["%$q%"])->fetchAll();
    }
    static function update($id, $n, $phone, $addr, $pass = '') {
        if ($pass !== '') Database::q('UPDATE users SET name=?,phone=?,address=?,password=? WHERE id=? AND role="user"',
            [$n, $phone, $addr, password_hash($pass, PASSWORD_DEFAULT), $id]);
        else Database::q('UPDATE users SET name=?,phone=?,address=? WHERE id=? AND role="user"', [$n, $phone, $addr, $id]);
    }
    static function toggle($id) {
        Database::q('UPDATE users SET status=IF(status="Aktif","Nonaktif","Aktif") WHERE id=? AND role="user"', [$id]);
    }
    static function delete($id) { Database::q('DELETE FROM users WHERE id=? AND role="user"', [$id]); }
    static function count() { return Database::q('SELECT COUNT(*) c FROM users WHERE role="user"')->fetch()['c']; }
}
