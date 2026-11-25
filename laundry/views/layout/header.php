<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryBoo</title>

    <!-- Font tulisan tangan -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Patrick+Hand&display=swap">

    <!-- Bootstrap CSS (harus sebelum laundry.css) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS LaundryBoo -->
    <link rel="stylesheet" href="<?= ASSET_URL ?>/laundry.css">
</head>
<body>

<?php if (!empty($_SESSION['user'])): $role = $_SESSION['user']['role']; ?>
<nav class="navbar navbar-expand navbar-light bg-white border-bottom mb-3">
  <div class="container">
    <span class="navbar-brand">🧺 LaundryBoo</span>
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
  </div>
</nav>
<?php endif; ?>

<?php if (!empty($_SESSION['flash'])): [$t, $m] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
<div class="container">
  <div class="alert alert-<?= e($t) ?> alert-dismissible fade show">
    <?= e($m) ?>
    <button class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>
<main class="container">
