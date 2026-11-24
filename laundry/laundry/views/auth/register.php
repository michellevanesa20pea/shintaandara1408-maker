<div class="row justify-content-center"><div class="col-md-4"><div class="card shadow-sm"><div class="card-body">
<h4 class="mb-3">Register</h4>
<form method="post" action="<?= url('auth/register') ?>"><?= csrf_field() ?>
  <div class="mb-3"><label class="form-label">Nama</label><input name="name" class="form-control" required></div>
  <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
  <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" minlength="6" class="form-control" required></div>
  <button class="btn btn-success w-100">Daftar</button>
</form>
<p class="mt-3 mb-0 small">Sudah punya akun? <a href="<?= url('auth/login') ?>">Login</a></p>
</div></div></div></div>
