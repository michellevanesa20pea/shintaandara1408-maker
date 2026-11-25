<?php $edit = (bool)$order; ?>
<div class="row justify-content-center"><div class="col-md-6"><div class="card"><div class="card-body">
<h4><?= $edit ? 'Edit Pesanan #' . $order['id'] : 'Pesanan Baru' ?></h4>
<form id="formPesanan" method="post" action="<?= url($edit ? 'user/update' : 'user/store') ?>"><?= csrf_field() ?>
<?php if ($edit): ?><input type="hidden" name="id" value="<?= $order['id'] ?>"><?php endif; ?>
<div class="mb-2"><label class="form-label">Layanan</label>
<select name="layanan_ui" id="layanan" class="form-select" onchange="this.form.service_id.value=this.value">
<?php foreach ($services as $s): ?>
<option value="<?= $s['id'] ?>" data-harga="<?= $s['price_per_kg'] ?>" <?= ($order['service_id'] ?? 0) == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?> — <?= rp($s['price_per_kg']) ?>/kg</option>
<?php endforeach; ?></select>
<input type="hidden" name="service_id" value="<?= e($order['service_id'] ?? ($services[0]['id'] ?? '')) ?>"></div>
<div class="mb-2"><label class="form-label">Berat (kg)</label><input type="number" step="0.1" min="0.1" max="100" name="weight" class="form-control" value="<?= e($order['weight'] ?? '') ?>" required></div>
<div class="row mb-2"><div class="col"><label class="form-label">Tanggal masuk</label><input type="date" name="entry_date" class="form-control" value="<?= e($order['entry_date'] ?? date('Y-m-d')) ?>" required></div>
<div class="col"><label class="form-label">Tanggal selesai</label><input type="date" name="finish_date" class="form-control" value="<?= e($order['finish_date'] ?? '') ?>" required></div></div>
<div class="mb-2"><label class="form-label d-block">Metode pembayaran</label>
<?php foreach (['Cash', 'QRIS'] as $m): ?><label class="me-3"><input type="radio" name="payment_method" value="<?= $m ?>" <?= ($order['payment_method'] ?? 'Cash') === $m ? 'checked' : '' ?>> <?= $m ?></label><?php endforeach; ?>
<div class="redup small">Status lunas ditandai oleh admin setelah pembayaran diterima.</div></div>
<div class="mb-3"><label class="form-label">Catatan</label><textarea name="notes" class="form-control"><?= e($order['notes'] ?? '') ?></textarea></div>
<p>Total: <b id="total">Rp 0</b></p>
<button class="btn utama">Simpan</button> <a class="btn" href="<?= url('user/dashboard') ?>">Kembali</a>
</form></div></div></div></div>
