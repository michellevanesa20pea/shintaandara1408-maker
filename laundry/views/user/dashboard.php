<div class="d-flex justify-content-between align-items-center mb-3">
<h4 class="mb-0">Hi, <?= e($_SESSION['user']['name']) ?> Boo</h4>
<a class="btn utama" href="<?= url('user/create') ?>">+ Pesanan Baru</a></div>
<?php foreach ($orders as $o): $cls = $o['status'] === 'Diproses' ? 'proses' : (in_array($o['status'], ['Selesai', 'Diambil']) ? 'selesai' : ''); ?>
<div class="kartu <?= $cls ?>">
  <div class="d-flex justify-content-between"><span><?= e($o['service_name']) ?>, <?= e($o['weight']) ?> kg</span>
  <span class="lencana <?= e($o['status']) ?>"><?= e($o['status']) ?></span></div>
  <p class="redup small mb-1">Masuk <?= tgl($o['entry_date']) ?>, estimasi selesai <?= tgl($o['finish_date']) ?></p>
  <b><?= rp($o['total']) ?></b> <span class="redup small">(<?= e($o['payment_method']) ?>, <?= $o['paid'] ? 'lunas' : 'belum bayar' ?>)</span>
  <div class="mt-2">
    <a class="btn kecil-btn" href="<?= url('user/receipt&id=' . $o['id']) ?>">Cetak struk</a>
    <?php if ($o['status'] === 'Menunggu'): ?>
    <a class="btn kecil-btn" href="<?= url('user/edit&id=' . $o['id']) ?>">Edit</a>
    <form method="post" action="<?= url('user/delete') ?>" class="d-inline" data-judul="Batalkan pesanan?" data-konfirmasi="Pesanan akan dihapus."><?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $o['id'] ?>"><button class="btn kecil-btn bahaya">Batal</button></form>
    <?php else: ?><span class="redup small">Pesanan sudah diproses admin (terkunci)</span><?php endif; ?>
  </div>
</div>
<?php endforeach; if (!$orders): ?><p class="redup">Belum ada pesanan. Buat lewat tombol Pesanan Baru.</p><?php endif; ?>
