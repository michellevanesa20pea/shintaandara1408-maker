<?php
class LayananController extends Controller {
    function daftar() { $this->auth(); $this->ok(Layanan::semua()); }
}
