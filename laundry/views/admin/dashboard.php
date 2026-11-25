<h4 class="mb-3">Dashboard Admin</h4>
<div class="row g-3 mb-4">
<?php foreach ([['Total Pesanan', $s['total']], ['Menunggu', $s['menunggu']], ['Diproses', $s['diproses']],
  ['Selesai', $s['selesai']], ['Pelanggan', $users], ['Pendapatan (lunas)', rp($s['omzet'])]] as [$l, $v]): ?>
  <div class="col-6 col-md-2"><div class="card"><div class="card-body p-3"><div class="redup small"><?= $l ?></div><div class="fs-5"><?= e($v) ?></div></div></div></div>
<?php endforeach; ?>
</div>
<div class="card"><div class="card-header bg-white">Pesanan Terbaru</div>
<table class="table mb-0"><tr><th>#</th><th>Pelanggan</th><th>Layanan</th><th>Berat</th><th>Total</th><th>Status</th></tr>
<?php foreach ($recent as $o): ?>
<tr><td><?= $o['id'] ?></td><td><?= e($o['customer']) ?></td><td><?= e($o['service_name']) ?></td><td><?= e($o['weight']) ?> kg</td><td><?= rp($o['total']) ?></td>
<td><span class="lencana <?= e($o['status']) ?>"><?= e($o['status']) ?></span></td></tr>
<?php endforeach; if (!$recent): ?><tr><td colspan="6" class="text-center redup">Belum ada pesanan.</td></tr><?php endif; ?>
</table></div>
<a class="btn utama mt-3" href="<?= url('admin/orders') ?>">Kelola Pesanan</a>
<a class="btn mt-3" href="<?= url('admin/customers') ?>">Kelola Akun Pelanggan</a>
