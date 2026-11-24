<h4 class="mb-3">Pesanan Masuk dari User</h4>
<div class="table-responsive"><table class="table table-striped bg-white align-middle">
<tr><th>#</th><th>Pelanggan</th><th>Layanan</th><th>Berat</th><th>Total</th><th>Jemput</th><th>Catatan</th><th>Status</th><th></th></tr>
<?php foreach ($orders as $o): ?>
<tr><td><?= $o['id'] ?></td><td><?= e($o['customer']) ?></td><td><?= e($o['service_name']) ?></td>
<td><?= e($o['weight']) ?> kg</td><td><?= rp($o['total']) ?></td><td><?= e($o['pickup_date']) ?></td><td><?= e($o['notes']) ?></td>
<td><form method="post" action="<?= url('admin/orderStatus') ?>" class="d-flex gap-1"><?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $o['id'] ?>">
<select name="status" class="form-select form-select-sm">
<?php foreach (['Menunggu', 'Diproses', 'Selesai', 'Diambil'] as $st): ?>
<option <?= $st === $o['status'] ? 'selected' : '' ?>><?= $st ?></option><?php endforeach; ?></select>
<button class="btn btn-sm btn-primary">Ubah</button></form></td>
<td><form method="post" action="<?= url('admin/orderDelete') ?>" onsubmit="return confirm('Hapus pesanan?')"><?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $o['id'] ?>"><button class="btn btn-sm btn-danger">Hapus</button></form></td></tr>
<?php endforeach; if (!$orders): ?><tr><td colspan="9" class="text-center text-muted">Belum ada pesanan.</td></tr><?php endif; ?>
</table></div>
