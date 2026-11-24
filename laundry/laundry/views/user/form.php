<?php $edit = (bool)$order; ?>
<div class="row justify-content-center"><div class="col-md-6"><div class="card shadow-sm"><div class="card-body">
<h4 class="mb-3"><?= $edit ? 'Edit Pesanan #' . $order['id'] : 'Pesan Laundry' ?></h4>
<form method="post" action="<?= url($edit ? 'user/update' : 'user/store') ?>"><?= csrf_field() ?>
<?php if ($edit): ?><input type="hidden" name="id" value="<?= $order['id'] ?>"><?php endif; ?>
<div class="mb-3"><label class="form-label">Layanan</label>
<select name="service_id" class="form-select" required>
<?php foreach ($services as $s): ?>
<option value="<?= $s['id'] ?>" <?= ($order['service_id'] ?? 0) == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?> — <?= rp($s['price_per_kg']) ?>/kg</option>
<?php endforeach; ?></select></div>
<div class="mb-3"><label class="form-label">Berat (kg)</label><input type="number" step="0.1" min="0.1" max="100" name="weight" class="form-control" value="<?= e($order['weight'] ?? '') ?>" required></div>
<div class="mb-3"><label class="form-label">Tanggal Jemput</label><input type="date" name="pickup_date" class="form-control" value="<?= e($order['pickup_date'] ?? '') ?>" required></div>
<div class="mb-3"><label class="form-label">Catatan</label><textarea name="notes" class="form-control"><?= e($order['notes'] ?? '') ?></textarea></div>
<button class="btn btn-primary">Simpan</button> <a class="btn btn-secondary" href="<?= url('user/dashboard') ?>">Kembali</a>
</form></div></div></div></div>
