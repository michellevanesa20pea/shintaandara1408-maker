// JS hanya untuk interaksi tampilan. Semua data & logika bisnis ada di server (PHP/MySQL).
const $ = id => document.getElementById(id);
const aman = t => String(t ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const rupiah = n => 'Rp ' + Number(n).toLocaleString('id-ID');

function tampilToast(pesan, gagal = false) {
    const t = $('toast'); t.textContent = pesan;
    t.className = 'notif' + (gagal ? ' gagal' : ''); t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 2600);
}
function tampilModal(html) { $('kotakModal').innerHTML = html; $('latarModal').classList.add('tampil'); }
function tutupModal() { $('latarModal').classList.remove('tampil'); }
$('latarModal').addEventListener('click', e => { if (e.target.id === 'latarModal') tutupModal(); });

function tanyaKonfirmasi(judul, ket, jikaYa) {
    tampilModal(`<h3>${aman(judul)}</h3><p class="redup">${aman(ket)}</p>
        <div class="baris"><button class="btn bahaya" id="btnYa">Ya</button><button class="btn" id="btnBatal">Batal</button></div>`);
    $('btnYa').onclick = () => { tutupModal(); jikaYa(); };
    $('btnBatal').onclick = tutupModal;
}

// Form dengan data-konfirmasi="..." meminta konfirmasi lewat modal sebelum dikirim ke server
document.addEventListener('submit', e => {
    const f = e.target;
    if (f.dataset.konfirmasi) {
        e.preventDefault();
        tanyaKonfirmasi(f.dataset.judul || 'Apakah Anda yakin?', f.dataset.konfirmasi, () => f.submit());
    }
});

// Tombol cetak struk
document.querySelectorAll('[data-cetak]').forEach(b => b.onclick = () => window.print());

// Form pesanan: hitung total otomatis + validasi tanggal (server tetap memvalidasi ulang)
const fp = $('formPesanan');
if (fp) {
    const hitung = () => {
        const h = +fp.layanan.selectedOptions[0].dataset.harga || 0;
        $('total').textContent = rupiah((parseFloat(fp.weight.value) || 0) * h);
    };
    fp.layanan.onchange = hitung; fp.weight.oninput = hitung; hitung();
    fp.onsubmit = e => {
        if (fp.finish_date.value < fp.entry_date.value) {
            e.preventDefault();
            tampilToast('Gagal: tanggal selesai lebih awal dari tanggal masuk', true);
        }
    };
}
