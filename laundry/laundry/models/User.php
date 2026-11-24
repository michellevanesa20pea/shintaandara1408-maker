<?php
class User {
    static function byEmail($e) { return Database::q('SELECT * FROM users WHERE email=?', [$e])->fetch(); }
    static function create($n, $e, $p) {
        Database::q('INSERT INTO users(name,email,password,role) VALUES(?,?,?,"user")', [$n, $e, password_hash($p, PASSWORD_DEFAULT)]);
    }
    static function count() { return Database::q('SELECT COUNT(*) c FROM users WHERE role="user"')->fetch()['c']; }
}
