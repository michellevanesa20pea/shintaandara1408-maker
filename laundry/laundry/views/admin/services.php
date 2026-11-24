<h4 class="mb-3">Kelola Layanan Laundry</h4>
<div class="card mb-4"><div class="card-header"><?= $edit ? 'Edit Layanan' : 'Tambah Layanan' ?></div><div class="card-body">
<form method="post" action="<?= url('admin/serviceSave') ?>" class="row g-2"><?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
  <div class="col-md-3"><input name="name" class="form-control" placeholder="Nama layanan" value="<?= e($edit['name'] ?? '') ?>" required></div>
  <div class="col-md-2"><input type="number" name="price" min="1" class="form-control" placeholder="Harga/kg" value="<?= e($edit['price_per_kg'] ?? '') ?>" required></div>
  <div class="col-md-4"><input name="description" class="form-control" placeholder="Deskripsi" value="<?= e($edit['description'] ?? '') ?>"></div>
  <div class="col-md-3"><button class="btn btn-primary">Simpan</button>
    <?php if ($edit): ?><a class="btn btn-secondary" href="<?= url('admin/services') ?>">Batal</a><?php endif; ?></div>
</form></div></div>
<table class="table table-striped bg-white"><tr><th>Nama</th><th>Harga/kg</th><th>Deskripsi</th><th width="160">Aksi</th></tr>
<?php foreach ($services as $s): ?>
<tr><td><?= e($s['name']) ?></td><td><?= rp($s['price_per_kg']) ?></td><td><?= e($s['description']) ?></td>
<td><a class="btn btn-sm btn-warning" href="<?= url('admin/services&id=' . $s['id']) ?>">Edit</a>
<form method="post" action="<?= url('admin/serviceDelete') ?>" class="d-inline" onsubmit="return confirm('Hapus layanan ini?')"><?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $s['id'] ?>"><button class="btn btn-sm btn-danger">Hapus</button></form></td></tr>
<?php endforeach; ?></table>
