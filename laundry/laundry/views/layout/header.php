<!DOCTYPE html><html lang="id"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>CleanFast Laundry</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<nav class="navbar navbar-expand navbar-dark bg-primary mb-4"><div class="container">
  <span class="navbar-brand">🧺 CleanFast Laundry</span>
  <?php if (!empty($_SESSION['user'])): $role = $_SESSION['user']['role']; ?>
  <div class="navbar-nav me-auto">
    <?php if ($role === 'admin'): ?>
      <a class="nav-link" href="<?= url('admin/dashboard') ?>">Dashboard</a>
      <a class="nav-link" href="<?= url('admin/services') ?>">Layanan</a>
      <a class="nav-link" href="<?= url('admin/orders') ?>">Pesanan</a>
    <?php else: ?>
      <a class="nav-link" href="<?= url('user/dashboard') ?>">Pesanan Saya</a>
      <a class="nav-link" href="<?= url('user/create') ?>">Pesan Laundry</a>
    <?php endif; ?>
  </div>
  <span class="navbar-text me-3"><?= e($_SESSION['user']['name']) ?> (<?= e($role) ?>)</span>
  <a class="btn btn-light btn-sm" href="<?= url('auth/logout') ?>">Logout</a>
  <?php endif; ?>
</div></nav>
<div class="container">
<?php if (!empty($_SESSION['flash'])): [$t, $m] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
  <div class="alert alert-<?= e($t) ?> alert-dismissible fade show"><?= e($m) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
