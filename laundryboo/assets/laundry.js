// LaundryBoo - JavaScript frontend. Semua data diambil/disimpan lewat API PHP (PDO + MySQL), bukan localStorage.
const $ = id => document.getElementById(id);
const aman = t => String(t ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
const rupiah = n => 'Rp ' + Number(n).toLocaleString('id-ID');
const hariIni = () => {
    const d = new Date();
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
};
const tanggal = t => t ? new Date(t + 'T00:00:00').toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';

const halaman = { peran: $('halamanPeran'), masuk: $('halamanMasuk'), daftar: $('halamanDaftar'), aplikasi: $('halamanAplikasi') };
const isi = { transaksi: $('isiTransaksi'), kelolaakun: $('isiKelolaakun'), pesanansaya: $('isiPesanansaya'), pesananbaru: $('isiPesananbaru') };
const btn = {
    admin: $('btnAdmin'), pelanggan: $('btnpelanggan'), keDaftar: $('btnKeDaftar'), keMasuk: $('btnKeMasuk'),
    kembaliMasuk: $('btnKembaliMasuk'), kembaliDaftar: $('btnKembaliDaftar'),
    transaksi: $('btnTransaksi'), kelolaakun: $('btnKelolaakun'), pesanansaya: $('btnPesanansaya'), pesananbaru: $('btnPesananbaru'),
    keluar: $('btnKeluar'), cash: $('btnCash'), qris: $('btnQris')
};
const form = {
    masuk: $('formMasuk'), daftar: $('formDaftar'), pesanan: $('formPesanan'),
    namaMasuk: $('inputNamaMasuk'), sandiMasuk: $('inputSandiMasuk'),
    namaDaftar: $('inputNamaDaftar'), telpDaftar: $('inputTelpDaftar'), alamatDaftar: $('inputAlamatDaftar'), sandiDaftar: $('inputSandiDaftar'),
    nama: $('pbNama'), telp: $('pbTelp'), alamat: $('pbAlamat'), tglMasuk: $('pbTglMasuk'), tglSelesai: $('pbTglSelesai'),
    layanan: $('pbLayanan'), berat: $('pbBerat'), harga: $('pbHarga'), total: $('pbTotal')
};
const tabel = { pesanan: $('tabelPesanan'), akun: $('tabelAkun'), pesananSaya: $('daftarPesananSaya') };
const daftarStatus = ['Menunggu', 'Diproses', 'Selesai', 'Diambil'];

// State (hanya cache tampilan; sumber data sebenarnya ada di database)
let peranDipilih = 'pelanggan', akunAktif = null, layanan = [], pesanan = [], akun = [], metodeBayar = '';

/* ---------- KOMUNIKASI KE SERVER ---------- */
// Mengembalikan data jika sukses, null jika gagal (pesan gagal otomatis tampil sebagai toast)
async function api(path, data) {
    const opsi = { headers: { 'X-Requested-With': 'fetch' } };
    if (data !== undefined) {
        opsi.method = 'POST';
        opsi.headers['Content-Type'] = 'application/json';
        opsi.body = JSON.stringify(data);
    }
    try {
        const res = await fetch('index.php?r=' + path, opsi);
        const j = await res.json();
        if (!j.ok) {
            if (res.status === 401 && akunAktif) { akunAktif = null; tampilHalaman('peran'); }
            tampilToast('Gagal: ' + j.pesan, true);
            return null;
        }
        return j.data;
    } catch (e) {
        tampilToast('Gagal terhubung ke server', true);
        return null;
    }
}

/* ---------- NOTIFIKASI, MODAL, NAVIGASI ---------- */
function tampilToast(pesan, gagal = false) {
    const t = $('toast');
    t.textContent = pesan;
    t.className = 'toast' + (gagal ? ' gagal' : '');
    t.style.display = 'block';
    clearTimeout(tampilToast.t);
    tampilToast.t = setTimeout(() => { t.style.display = 'none'; }, 2600);
}
function tampilModal(html) { $('kotakModal').innerHTML = html; $('latarModal').classList.add('tampil'); }
function tutupModal() { $('latarModal').classList.remove('tampil'); }
$('latarModal').addEventListener('click', e => { if (e.target.id === 'latarModal') tutupModal(); });

function tanyaKonfirmasi(judul, keterangan, jikaYa) {
    tampilModal(`<h3>${aman(judul)}</h3><p class="redup">${aman(keterangan)}</p>
        <div class="baris"><button class="btn bahaya" id="btnYa">Ya</button><button class="btn" id="btnBatal">Batal</button></div>`);
    $('btnYa').onclick = () => { tutupModal(); jikaYa(); };
    $('btnBatal').onclick = tutupModal;
}
function tampilHalaman(nama) { Object.keys(halaman).forEach(k => halaman[k].classList.toggle('aktif', k === nama)); }
function tampilIsi(nama) {
    Object.keys(isi).forEach(k => {
        isi[k].classList.toggle('aktif', k === nama);
        btn[k].classList.toggle('aktif', k === nama);
    });
}

/* ---------- PILIH PERAN, LOGIN, DAFTAR ---------- */
function pilihPeran(peran) {
    peranDipilih = peran;
    form.masuk.reset();
    // Akun admin tidak bisa didaftarkan sendiri (dibuat lewat database)
    btn.keDaftar.parentElement.style.display = peran === 'admin' ? 'none' : '';
    $('judulMasuk').textContent = 'Login sebagai ' + peran;
    tampilHalaman('masuk');
}
btn.admin.onclick = () => pilihPeran('admin');
btn.pelanggan.onclick = () => pilihPeran('pelanggan');
btn.kembaliMasuk.onclick = () => tampilHalaman('peran');
btn.kembaliDaftar.onclick = () => tampilHalaman('masuk');
btn.keDaftar.onclick = e => {
    e.preventDefault();
    form.daftar.reset();
    document.querySelectorAll('.khusus-admin-daftar').forEach(el => { el.style.display = 'none'; });
    document.querySelectorAll('.khusus-pelanggan-daftar').forEach(el => { el.style.display = 'block'; });
    form.telpDaftar.required = true;
    form.alamatDaftar.required = true;
    $('judulDaftar').textContent = 'Buat akun pelanggan';
    tampilHalaman('daftar');
};
btn.keMasuk.onclick = e => { e.preventDefault(); tampilHalaman('masuk'); };

form.daftar.onsubmit = async e => {
    e.preventDefault();
    const r = await api('auth/register', {
        nama: form.namaDaftar.value.trim(), telp: form.telpDaftar.value.trim(),
        alamat: form.alamatDaftar.value.trim(), sandi: form.sandiDaftar.value
    });
    if (r === null) return;
    peranDipilih = 'pelanggan';
    tampilToast('Akun berhasil dibuat, silakan login');
    tampilHalaman('masuk');
};
form.masuk.onsubmit = async e => {
    e.preventDefault();
    const r = await api('auth/login', { nama: form.namaMasuk.value.trim(), sandi: form.sandiMasuk.value, peran: peranDipilih });
    if (r === null) return;
    akunAktif = r.user;
    masukAplikasi();
};

async function masukAplikasi() {
    const admin = akunAktif.peran === 'admin';
    document.querySelectorAll('.khusus-admin').forEach(el => { el.style.display = admin ? 'block' : 'none'; });
    document.querySelectorAll('.khusus-pelanggan').forEach(el => { el.style.display = admin ? 'none' : 'block'; });
    $('sapaan').textContent = 'Hi, ' + akunAktif.nama + ' Boo';
    const l = await api('layanan/daftar');
    if (l !== null) layanan = l;
    tampilHalaman('aplikasi');
    if (admin) bukaTransaksi(); else bukaPesanansaya();
    tampilToast('Selamat datang, ' + akunAktif.nama);
}
btn.keluar.onclick = () => tanyaKonfirmasi('Apakah Anda ingin keluar dari akun ini?', 'Setelah keluar Anda perlu login kembali.', async () => {
    await api('auth/logout', {});
    akunAktif = null;
    tampilHalaman('peran');
});
btn.transaksi.onclick = bukaTransaksi;
btn.kelolaakun.onclick = bukaKelolaakun;
btn.pesanansaya.onclick = bukaPesanansaya;
btn.pesananbaru.onclick = bukaPesananbaru;

/* ---------- ADMIN: KELOLA PESANAN ---------- */
async function bukaTransaksi() {
    tampilIsi('transaksi');
    const r = await api('pesanan/daftar');
    if (r !== null) { pesanan = r; gambarTabelPesanan(); }
}
$('cariPesanan').oninput = gambarTabelPesanan;
function gambarTabelPesanan() {
    const kata = $('cariPesanan').value.toLowerCase();
    const baris = pesanan.filter(p => p.nama.toLowerCase().includes(kata));
    tabel.pesanan.innerHTML = baris.length ? baris.map(p => `
        <tr>
            <td>${aman(p.nama)}</td><td>${aman(p.telp)}</td><td>${tanggal(p.tglMasuk)}</td><td>${tanggal(p.tglSelesai)}</td>
            <td>${aman(p.layanan)}</td><td>${p.berat} kg</td><td>${rupiah(p.total)}</td>
            <td>${aman(p.metode)} ${p.sudahBayar ? '(lunas)' : '(belum bayar)'}</td>
            <td><span class="lencana ${aman(p.status)}">${aman(p.status)}</span></td>
            <td>
                <button class="btn kecil-btn" data-struk="${p.id}">Struk</button>
                <button class="btn kecil-btn" data-edit="${p.id}">Edit</button>
                <button class="btn kecil-btn bahaya" data-hapus="${p.id}">Hapus</button>
            </td>
        </tr>`).join('')
        : '<tr><td colspan="10" class="redup">Belum ada pesanan.</td></tr>';
}
tabel.pesanan.onclick = e => {
    const t = e.target.closest('button');
    if (!t) return;
    if (t.dataset.struk) tampilStruk(+t.dataset.struk);
    if (t.dataset.edit) editPesanan(+t.dataset.edit);
    if (t.dataset.hapus) {
        tanyaKonfirmasi('Apakah Anda ingin menghapus data pesanan?', 'Data yang dihapus tidak dapat dikembalikan.', async () => {
            if (await api('pesanan/hapus', { id: +t.dataset.hapus }) === null) return;
            await bukaTransaksi();
            tampilToast('Data berhasil dihapus');
        });
    }
};
function editPesanan(id) {
    const p = pesanan.find(x => x.id === id);
    if (!p) return;
    tampilModal(`
        <h3>Edit pesanan</h3>
        <label>Nama lengkap</label><input value="${aman(p.nama)}" readonly>
        <label>Berat (kg)</label><input id="edBerat" type="number" min="0.5" step="0.5" value="${p.berat}">
        <label>Layanan</label>
        <select id="edLayanan">${layanan.map(l => `<option value="${l.id}" ${l.id === p.layananId ? 'selected' : ''}>${aman(l.nama)}</option>`).join('')}</select>
        <label>Tanggal selesai</label><input id="edTglSelesai" type="date" value="${aman(p.tglSelesai)}">
        <label>Status</label>
        <select id="edStatus">${daftarStatus.map(s => `<option ${s === p.status ? 'selected' : ''}>${s}</option>`).join('')}</select>
        <label>Pembayaran (${aman(p.metode)})</label>
        <select id="edBayar"><option value="0" ${p.sudahBayar ? '' : 'selected'}>Belum bayar</option><option value="1" ${p.sudahBayar ? 'selected' : ''}>Lunas</option></select>
        <div class="baris"><button class="btn utama" id="btnSimpan">Simpan</button><button class="btn" id="btnBatal">Batal</button></div>`);
    $('btnBatal').onclick = tutupModal;
    $('btnSimpan').onclick = async () => {
        const berat = parseFloat($('edBerat').value), tglSelesai = $('edTglSelesai').value;
        if (!(berat > 0)) return tampilToast('Gagal: berat tidak valid', true);
        if (!tglSelesai) return tampilToast('Gagal: tanggal selesai wajib diisi', true);
        if (tglSelesai < p.tglMasuk) return tampilToast('Gagal: tanggal selesai lebih awal dari tanggal masuk', true);
        const r = await api('pesanan/ubah', {
            id: p.id, berat, tglSelesai, layananId: +$('edLayanan').value,
            status: $('edStatus').value, sudahBayar: $('edBayar').value === '1'
        });
        if (r === null) return;
        tutupModal();
        await bukaTransaksi();
        tampilToast('Data berhasil disimpan');
    };
}

/* ---------- ADMIN: KELOLA AKUN PELANGGAN ---------- */
async function bukaKelolaakun() {
    tampilIsi('kelolaakun');
    const r = await api('akun/daftar');
    if (r !== null) { akun = r; gambarTabelAkun(); }
}
$('cariAkun').oninput = gambarTabelAkun;
function gambarTabelAkun() {
    const kata = $('cariAkun').value.toLowerCase();
    const baris = akun.filter(a => a.nama.toLowerCase().includes(kata));
    tabel.akun.innerHTML = baris.length ? baris.map(a => `
        <tr>
            <td>${aman(a.nama)}</td><td>${aman(a.telp)}</td>
            <td><span class="lencana ${aman(a.status)}">${aman(a.status)}</span></td>
            <td>
                <button class="btn kecil-btn" data-status="${a.id}">${a.status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan'}</button>
                <button class="btn kecil-btn" data-edit="${a.id}">Edit</button>
                <button class="btn kecil-btn bahaya" data-hapus="${a.id}">Hapus</button>
            </td>
        </tr>`).join('')
        : '<tr><td colspan="4" class="redup">Belum ada akun pelanggan.</td></tr>';
}
tabel.akun.onclick = async e => {
    const t = e.target.closest('button');
    if (!t) return;
    const { edit, hapus, status } = t.dataset;
    if (status) {
        if (await api('akun/status', { id: +status }) === null) return;
        await bukaKelolaakun();
        tampilToast('Status akun diperbarui');
    }
    if (edit) editAkun(+edit);
    if (hapus) {
        tanyaKonfirmasi('Apakah Anda ingin menghapus akun pelanggan?', 'Pesanan milik akun ini ikut terhapus.', async () => {
            if (await api('akun/hapus', { id: +hapus }) === null) return;
            await bukaKelolaakun();
            tampilToast('Data berhasil dihapus');
        });
    }
};
function editAkun(id) {
    const a = akun.find(x => x.id === id);
    if (!a) return;
    tampilModal(`
        <h3>Edit akun pelanggan</h3>
        <label>Nama lengkap</label><input id="edNama" value="${aman(a.nama)}">
        <label>Password baru (kosongkan jika tidak diubah)</label><input id="edSandi" type="password">
        <label>Verifikasi password</label><input id="edSandi2" type="password">
        <div class="baris"><button class="btn utama" id="btnSimpan">Simpan</button><button class="btn" id="btnBatal">Batal</button></div>`);
    $('btnBatal').onclick = tutupModal;
    $('btnSimpan').onclick = async () => {
        const nama = $('edNama').value.trim(), sandi = $('edSandi').value;
        if (!nama) return tampilToast('Gagal: nama wajib diisi', true);
        if (sandi && sandi !== $('edSandi2').value) return tampilToast('Gagal: verifikasi password tidak sama', true);
        if (await api('akun/ubah', { id: a.id, nama, sandi }) === null) return;
        tutupModal();
        await bukaKelolaakun();
        tampilToast('Data berhasil disimpan');
    };
}

/* ---------- PELANGGAN: PESANAN SAYA ---------- */
async function bukaPesanansaya() {
    tampilIsi('pesanansaya');
    const r = await api('pesanan/daftar');
    if (r === null) return;
    pesanan = r;
    tabel.pesananSaya.innerHTML = r.length ? r.map(p => `
        <div class="kartu ${p.status === 'Diproses' ? 'proses' : (p.status === 'Selesai' || p.status === 'Diambil') ? 'selesai' : ''}">
            <div class="atas">
                <span>${aman(p.layanan)}, ${p.berat} kg</span>
                <span class="lencana ${aman(p.status)}">${aman(p.status)}</span>
            </div>
            <p class="redup kecil">Masuk ${tanggal(p.tglMasuk)}, estimasi selesai ${tanggal(p.tglSelesai)}</p>
            <b>${rupiah(p.total)}</b>
            <span class="redup kecil">(${aman(p.metode)}${p.sudahBayar ? ', lunas' : ', belum bayar'})</span>
            <div><button class="btn kecil-btn" data-struk="${p.id}">Cetak struk</button></div>
        </div>`).join('')
        : '<p class="redup">Belum ada pesanan. Buat pesanan lewat menu Pesanan baru.</p>';
}
tabel.pesananSaya.onclick = e => {
    const t = e.target.closest('button');
    if (t && t.dataset.struk) tampilStruk(+t.dataset.struk);
};

/* ---------- PELANGGAN: PESANAN BARU ---------- */
function bukaPesananbaru() {
    tampilIsi('pesananbaru');
    form.pesanan.reset();
    form.layanan.innerHTML = layanan.map(l => `<option value="${l.id}" data-harga="${l.harga}">${aman(l.nama)}</option>`).join('');
    form.nama.value = akunAktif.nama;
    form.telp.value = akunAktif.telp;
    form.alamat.value = akunAktif.alamat;
    form.tglMasuk.value = hariIni();
    pilihMetode('');
    hitungTotal();
}
function hitungTotal() {
    const opsi = form.layanan.selectedOptions[0];
    const harga = opsi ? +opsi.dataset.harga : 0;
    form.harga.value = rupiah(harga) + ' / kg';
    form.total.value = rupiah((parseFloat(form.berat.value) || 0) * harga);
}
form.layanan.onchange = hitungTotal;
form.berat.oninput = hitungTotal;
function pilihMetode(m) {
    metodeBayar = m;
    btn.cash.classList.toggle('aktif', m === 'Cash');
    btn.qris.classList.toggle('aktif', m === 'QRIS');
}
btn.cash.onclick = () => pilihMetode('Cash');
btn.qris.onclick = () => pilihMetode('QRIS');

form.pesanan.onsubmit = e => {
    e.preventDefault();
    const berat = parseFloat(form.berat.value);
    if (!(berat > 0)) return tampilToast('Gagal: isi berat cucian', true);
    if (!form.tglSelesai.value) return tampilToast('Gagal: tanggal selesai wajib diisi', true);
    if (form.tglSelesai.value < form.tglMasuk.value) return tampilToast('Gagal: tanggal selesai lebih awal dari tanggal masuk', true);
    if (!metodeBayar) return tampilToast('Gagal: pilih metode pembayaran', true);
    const harga = +form.layanan.selectedOptions[0].dataset.harga, total = berat * harga;
    tampilModal(`
        <h3>Ringkasan pesanan</h3>
        <div class="ringkas">
            <p><span>Metode pembayaran</span><b>${aman(metodeBayar)}</b></p>
            <p><span>Layanan</span><b>${aman(form.layanan.selectedOptions[0].textContent)}</b></p>
            <p><span>Berat</span><b>${berat} kg</b></p>
            <p><span>Harga</span><b>${rupiah(harga)} / kg</b></p>
            <p><span>Total</span><b>${rupiah(total)}</b></p>
        </div>
        <div class="baris"><button class="btn utama" id="btnLanjut">Lanjut bayar</button><button class="btn" id="btnBatal">Batal</button></div>`);
    $('btnBatal').onclick = tutupModal;
    $('btnLanjut').onclick = () => metodeBayar === 'Cash' ? tampilBayarCash(total) : tampilBayarQris(total);
};
function tampilBayarCash(total) {
    tampilModal(`<h3>Silakan pergi ke kasir untuk melakukan pembayaran</h3>
        <p class="redup">Tunjukkan pesanan Anda. Total ${rupiah(total)}.</p>
        <div class="baris"><button class="btn utama" id="btnOk">Ok</button></div>`);
    $('btnOk').onclick = buatPesanan;
}
function tampilBayarQris(total) {
    let kotak = '';
    for (let i = 0; i < 441; i++) kotak += `<i class="${(i * 7 + (i % 5) * 3 + Math.floor(i / 21)) % 3 === 0 ? 'hitam' : ''}"></i>`;
    tampilModal(`<h3>QRIS</h3><div class="qr">${kotak}</div>
        <p class="redup" style="text-align:center">Scan untuk membayar ${rupiah(total)} (contoh tampilan).<br>Status lunas dikonfirmasi admin.</p>
        <div class="baris"><button class="btn utama" id="btnSudahBayar">Sudah bayar</button></div>`);
    $('btnSudahBayar').onclick = buatPesanan;
}
async function buatPesanan() {
    const r = await api('pesanan/buat', {
        layananId: +form.layanan.value, berat: parseFloat(form.berat.value),
        tglMasuk: form.tglMasuk.value, tglSelesai: form.tglSelesai.value,
        metode: metodeBayar, telp: form.telp.value.trim(), alamat: form.alamat.value.trim()
    });
    if (r === null) return;
    akunAktif.telp = form.telp.value.trim();
    akunAktif.alamat = form.alamat.value.trim();
    tutupModal();
    await bukaPesanansaya();
    tampilToast('Pesanan berhasil dibuat');
}

/* ---------- STRUK ---------- */
function tampilStruk(id) {
    const p = pesanan.find(x => x.id === id);
    if (!p) return;
    const baris = (k, v) => `<p><span>${aman(k)}</span><span>${aman(v)}</span></p>`;
    tampilModal(`
        <div class="struk" id="areaStruk">
            <div class="struk-judul">LaundryBoo</div><div class="struk-tengah">Struk Pesanan</div><hr>
            ${baris('No.', 'LB-' + String(p.id).padStart(4, '0'))}
            ${baris('Dicetak', new Date().toLocaleString('id-ID'))}
            ${baris('Pelanggan', p.nama)}
            ${baris('No. telp', p.telp || '-')}<hr>
            ${baris('Layanan', p.layanan)}
            ${baris('Berat', p.berat + ' kg')}
            ${baris('Harga/kg', rupiah(p.hargaKg))}
            ${baris('Tgl masuk', tanggal(p.tglMasuk))}
            ${baris('Tgl selesai', tanggal(p.tglSelesai))}<hr>
            <p class="struk-total"><span>TOTAL</span><span>${rupiah(p.total)}</span></p>
            ${baris('Bayar', p.metode + (p.sudahBayar ? ' (lunas)' : ' (belum bayar)'))}
            ${baris('Status', p.status)}<hr>
            <div class="struk-tengah">Terima kasih sudah mencuci di LaundryBoo</div>
        </div>
        <div class="baris"><button class="btn utama" id="btnCetak">Cetak</button><button class="btn" id="btnBatal">Tutup</button></div>`);
    $('btnBatal').onclick = tutupModal;
    $('btnCetak').onclick = () => window.print();
}

/* ---------- AWAL: pulihkan sesi jika masih login ---------- */
(async () => {
    const r = await api('auth/me');
    if (r && r.user) { akunAktif = r.user; masukAplikasi(); }
})();
