<div class="row justify-content-center"><div class="col-md-5"><div class="card"><div class="card-body">
<h4>Daftar Akun Pelanggan</h4>
<form method="post" action="<?= url('auth/register') ?>"><?= csrf_field() ?>
  <div class="mb-2"><label class="form-label">Nama lengkap</label><input name="name" class="form-control" required></div>
  <div class="mb-2"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
  <div class="mb-2"><label class="form-label">No. telp</label><input name="phone" class="form-control" required></div>
  <div class="mb-2"><label class="form-label">Alamat</label><input name="address" class="form-control" required></div>
  <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" minlength="6" class="form-control" required></div>
  <button class="btn utama w-100">Daftar</button>
</form>
<p class="mt-3 mb-0 small">Sudah punya akun? <a href="<?= url('auth/login') ?>">Login</a></p>
</div></div></div></div>
