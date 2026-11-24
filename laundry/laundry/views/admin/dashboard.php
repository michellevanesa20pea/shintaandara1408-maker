<h4 class="mb-3">Admin Dashboard</h4>
<div class="row g-3 mb-4">
<?php foreach ([['Total Pesanan', $s['total'], 'primary'], ['Menunggu', $s['menunggu'] ?? 0, 'warning'],
  ['Diproses', $s['diproses'] ?? 0, 'info'], ['Selesai', $s['selesai'] ?? 0, 'success'],
  ['Pelanggan', $users, 'secondary'], ['Omzet', rp($s['omzet']), 'dark']] as [$l, $v, $c]): ?>
  <div class="col-6 col-md-2"><div class="card text-bg-<?= $c ?>"><div class="card-body p-3"><div class="small"><?= $l ?></div><div class="fs-5 fw-bold"><?= e($v) ?></div></div></div></div>
<?php endforeach; ?>
</div>
<div class="card"><div class="card-header">Pesanan Terbaru dari User</div>
<table class="table mb-0"><tr><th>#</th><th>Pelanggan</th><th>Layanan</th><th>Berat</th><th>Total</th><th>Status</th></tr>
<?php foreach ($recent as $o): ?>
<tr><td><?= $o['id'] ?></td><td><?= e($o['customer']) ?></td><td><?= e($o['service_name']) ?></td><td><?= e($o['weight']) ?> kg</td><td><?= rp($o['total']) ?></td><td><?= e($o['status']) ?></td></tr>
<?php endforeach; if (!$recent): ?><tr><td colspan="6" class="text-center text-muted">Belum ada pesanan.</td></tr><?php endif; ?>
</table></div>
<a class="btn btn-primary mt-3" href="<?= url('admin/orders') ?>">Kelola Semua Pesanan</a>
