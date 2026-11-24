<h4 class="mb-3">Halo, <?= e($_SESSION['user']['name']) ?> 👋</h4>
<h6>Daftar Layanan (dari Admin)</h6>
<div class="row g-3 mb-4">
<?php foreach ($services as $s): ?>
<div class="col-md-4"><div class="card h-100"><div class="card-body">
<h5><?= e($s['name']) ?></h5><p class="text-muted small mb-1"><?= e($s['description']) ?></p>
<strong><?= rp($s['price_per_kg']) ?>/kg</strong></div></div></div>
<?php endforeach; ?></div>
<div class="d-flex justify-content-between mb-2"><h6>Pesanan Saya</h6>
<a class="btn btn-primary btn-sm" href="<?= url('user/create') ?>">+ Pesan Laundry</a></div>
<table class="table table-striped bg-white align-middle">
<tr><th>#</th><th>Layanan</th><th>Berat</th><th>Total</th><th>Jemput</th><th>Status (diatur Admin)</th><th>Aksi</th></tr>
<?php $badge = ['Menunggu' => 'warning', 'Diproses' => 'info', 'Selesai' => 'success', 'Diambil' => 'secondary'];
foreach ($orders as $o): ?>
<tr><td><?= $o['id'] ?></td><td><?= e($o['service_name']) ?></td><td><?= e($o['weight']) ?> kg</td><td><?= rp($o['total']) ?></td>
<td><?= e($o['pickup_date']) ?></td><td><span class="badge text-bg-<?= $badge[$o['status']] ?>"><?= e($o['status']) ?></span></td>
<td><?php if ($o['status'] === 'Menunggu'): ?>
<a class="btn btn-sm btn-warning" href="<?= url('user/edit&id=' . $o['id']) ?>">Edit</a>
<form method="post" action="<?= url('user/delete') ?>" class="d-inline" onsubmit="return confirm('Batalkan pesanan?')"><?= csrf_field() ?>
<input type="hidden" name="id" value="<?= $o['id'] ?>"><button class="btn btn-sm btn-danger">Batal</button></form>
<?php else: ?><span class="text-muted small">Terkunci</span><?php endif; ?></td></tr>
<?php endforeach; if (!$orders): ?><tr><td colspan="7" class="text-center text-muted">Belum ada pesanan.</td></tr><?php endif; ?>
</table>
