<div class="struk">
  <div class="struk-judul">LaundryBoo</div><div class="struk-tengah">Struk Pesanan</div><hr>
  <?php foreach ([['No.', 'LB-' . str_pad($o['id'], 4, '0', STR_PAD_LEFT)], ['Dicetak', date('d M Y H:i')], ['Pelanggan', $o['customer']], ['No. telp', $o['phone'] ?: '-']] as [$k, $v]): ?>
  <p><span><?= e($k) ?></span><span><?= e($v) ?></span></p><?php endforeach; ?><hr>
  <?php foreach ([['Layanan', $o['service_name']], ['Berat', $o['weight'] . ' kg'], ['Harga/kg', rp($o['price_per_kg'])], ['Tgl masuk', tgl($o['entry_date'])], ['Tgl selesai', tgl($o['finish_date'])]] as [$k, $v]): ?>
  <p><span><?= e($k) ?></span><span><?= e($v) ?></span></p><?php endforeach; ?><hr>
  <p class="struk-total"><span>TOTAL</span><span><?= rp($o['total']) ?></span></p>
  <p><span>Bayar</span><span><?= e($o['payment_method']) ?> (<?= $o['paid'] ? 'lunas' : 'belum bayar' ?>)</span></p>
  <p><span>Status</span><span><?= e($o['status']) ?></span></p><hr>
  <div class="struk-tengah">Terima kasih sudah mencuci di LaundryBoo</div>
</div>
<div class="text-center mt-3 tanpa-cetak"><button class="btn utama" data-cetak>Cetak</button>
<a class="btn" href="<?= url($_SESSION['user']['role'] . '/dashboard') ?>">Kembali</a></div>
