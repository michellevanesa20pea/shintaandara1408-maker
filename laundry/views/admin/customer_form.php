<div class="row justify-content-center"><div class="col-md-5"><div class="card"><div class="card-body">
<h4>Edit Akun Pelanggan</h4>
<form method="post" action="<?= url('admin/customerUpdate') ?>"><?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $u['id'] ?>">
<div class="mb-2"><label class="form-label">Nama</label><input name="name" class="form-control" value="<?= e($u['name']) ?>" required></div>
<div class="mb-2"><label class="form-label">Email</label><input class="form-control" value="<?= e($u['email']) ?>" disabled></div>
<div class="mb-2"><label class="form-label">No. telp</label><input name="phone" class="form-control" value="<?= e($u['phone']) ?>" required></div>
<div class="mb-2"><label class="form-label">Alamat</label><input name="address" class="form-control" value="<?= e($u['address']) ?>"></div>
<div class="mb-2"><label class="form-label">Password baru (kosongkan jika tidak diubah)</label><input type="password" name="password" class="form-control"></div>
<div class="mb-3"><label class="form-label">Verifikasi password</label><input type="password" name="password2" class="form-control"></div>
<button class="btn utama">Simpan</button> <a class="btn" href="<?= url('admin/customers') ?>">Kembali</a>
</form></div></div></div></div>
