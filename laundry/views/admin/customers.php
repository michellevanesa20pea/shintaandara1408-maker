<div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Akun Pelanggan</h4>
<form method="get" class="d-flex gap-1"><input type="hidden" name="r" value="admin/customers">
<input name="q" value="<?= e($q) ?>" class="form-control form-control-sm" placeholder="Cari nama"><button class="btn kecil-btn">Cari</button></form></div>
<table class="table align-middle"><tr><th>Nama</th><th>Email</th><th>No. telp</th><th>Pesanan</th><th>Status</th><th>Aksi</th></tr>
<?php foreach ($rows as $u): ?>
<tr><td><?= e($u['name']) ?></td><td><?= e($u['email']) ?></td><td><?= e($u['phone']) ?></td><td><?= $u['total_orders'] ?></td>
<td><span class="lencana <?= e($u['status']) ?>"><?= e($u['status']) ?></span></td>
<td class="text-nowrap">
<form method="post" action="<?= url('admin/customerToggle') ?>" class="d-inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $u['id'] ?>">
<button class="btn kecil-btn"><?= $u['status'] === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' ?></button></form>
<a class="btn kecil-btn" href="<?= url('admin/customerEdit&id=' . $u['id']) ?>">Edit</a>
<form method="post" action="<?= url('admin/customerDelete') ?>" class="d-inline" data-judul="Hapus akun pelanggan?" data-konfirmasi="Pesanan milik akun ini ikut terhapus."><?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $u['id'] ?>"><button class="btn kecil-btn bahaya">Hapus</button></form></td></tr>
<?php endforeach; if (!$rows): ?><tr><td colspan="6" class="text-center redup">Belum ada akun pelanggan.</td></tr><?php endif; ?>
</table>
