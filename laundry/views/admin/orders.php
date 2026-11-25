<div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Pesanan Masuk</h4>
<form method="get" class="d-flex gap-1"><input type="hidden" name="r" value="admin/orders">
<input name="q" value="<?= e($q) ?>" class="form-control form-control-sm" placeholder="Cari nama pelanggan"><button class="btn kecil-btn">Cari</button></form></div>
<div class="table-responsive"><table class="table align-middle">
<tr><th>Pelanggan</th><th>Layanan</th><th>Berat</th><th>Total</th><th>Masuk</th><th>Selesai</th><th>Bayar</th><th>Status</th><th>Aksi</th></tr>
<?php foreach ($orders as $o): ?>
<tr><td><?= e($o['customer']) ?><div class="redup small"><?= e($o['phone']) ?></div></td><td><?= e($o['service_name']) ?></td>
<td><?= e($o['weight']) ?> kg</td><td><?= rp($o['total']) ?></td><td><?= tgl($o['entry_date']) ?></td><td><?= tgl($o['finish_date']) ?></td>
<td><?= e($o['payment_method']) ?> <span class="redup small">(<?= $o['paid'] ? 'lunas' : 'belum bayar' ?>)</span></td>
<td><form method="post" action="<?= url('admin/orderStatus') ?>" class="d-flex gap-1"><?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $o['id'] ?>">
<select name="status" class="form-select form-select-sm">
<?php foreach (['Menunggu', 'Diproses', 'Selesai', 'Diambil'] as $st): ?><option <?= $st === $o['status'] ? 'selected' : '' ?>><?= $st ?></option><?php endforeach; ?></select>
<button class="btn kecil-btn">Ubah</button></form></td>
<td class="text-nowrap"><a class="btn kecil-btn" href="<?= url('admin/receipt&id=' . $o['id']) ?>">Struk</a>
<form method="post" action="<?= url('admin/orderPaid') ?>" class="d-inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $o['id'] ?>">
<button class="btn kecil-btn"><?= $o['paid'] ? 'Batal lunas' : 'Tandai lunas' ?></button></form>
<form method="post" action="<?= url('admin/orderDelete') ?>" class="d-inline" data-judul="Hapus pesanan?" data-konfirmasi="Data yang dihapus tidak dapat dikembalikan."><?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $o['id'] ?>"><button class="btn kecil-btn bahaya">Hapus</button></form></td></tr>
<?php endforeach; if (!$orders): ?><tr><td colspan="9" class="text-center redup">Belum ada pesanan.</td></tr><?php endif; ?>
</table></div>
