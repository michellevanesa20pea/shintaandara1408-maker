const $ = id => document.getElementById(id);

const halaman = {
    peran: $('halamanPeran'),
    masuk: $('halamanMasuk'),
    daftar: $('halamanDaftar'),
    aplikasi: $('halamanAplikasi')
};

const isi = {
    transaksi: $('isiTransaksi'),
    kelolaakun: $('isiKelolaakun'),
    pesanansaya: $('isiPesanansaya'),
    pesananbaru: $('isiPesananbaru')
};

const btn = {
    admin: $('btnAdmin'),
    pelanggan: $('btnUser'),
    masuk: $('btnMasuk'),
    daftar: $('btnDaftar'),
    keDaftar: $('btnKeDaftar'),
    keMasuk: $('btnKeMasuk'),
    kembaliMasuk: $('btnKembaliMasuk'),
    kembaliDaftar: $('btnKembaliDaftar'),
    transaksi: $('btnTransaksi'),
    kelolaakun: $('btnKelolaakun'),
    pesanansaya: $('btnPesanansaya'),
    pesananbaru: $('btnPesananbaru'),
    keluar: $('btnKeluar'),
    cash: $('btnCash'),
    qris: $('btnQris'),
    pesan: $('btnPesan')
};

const form = {
    masuk: $('formMasuk'),
    daftar: $('formDaftar'),
    pesanan: $('formPesanan'),

    namaMasuk: $('inputNamaMasuk'),
    sandiMasuk: $('inputSandiMasuk'),
    sandi2Daftar: $('inputSandi2Daftar'),
    namaDaftar: $('inputNamaDaftar'),
    telpDaftar: $('inputTelpDaftar'),
    alamatDaftar: $('inputAlamatDaftar'),
    sandiDaftar: $('inputSandiDaftar'),

    nama: $('pbNama'),
    telp: $('pbTelp'),
    alamat: $('pbAlamat'),
    tglMasuk: $('pbTglMasuk'),
    tglSelesai: $('pbTglSelesai'),
    layanan: $('pbLayanan'),
    berat: $('pbBerat'),
    harga: $('pbHarga'),
    total: $('pbTotal')
};
const tabel = {
    pesanan: $('tabelPesanan'),
    akun: $('tabelAkun'),
    pesananSaya: $('daftarPesananSaya')
};

/*DATA ADMIN, PELANGGAN, DAN PESANAN*/
const hargaLayanan = {
    'Cuci kering': 8000,
    'Cuci setrika': 10000,
    'Setrika saja': 6000
};
const daftarStatus = [
    'Menunggu',
    'Diproses',
    'Selesai',
    'Diambil'
];
const kunciSimpan = 'laundryboo-data';

/*Data Lama*/
function bacaData() {
    try {
        return JSON.parse(localStorage.getItem(kunciSimpan));
    } catch (e) {
        return null;
    }
}
const dataLama = bacaData() || {};
const akunLama = Array.isArray(dataLama.akun)
    ? dataLama.akun
    : [];

/* Data Admin dipisah*/
let dataAdmin = {
    akun: akunLama
        .filter(a => a.peran === 'admin')
        .map(a => ({
            ...a,
            peran: 'admin'
        }))
};

/*Data Pelanggan dipisah */
let dataPelanggan = {
    akun: akunLama
        .filter(a =>
            a.peran === 'pelanggan' ||
            a.peran === 'user'
        )
        .map(a => ({
            ...a,
            peran: 'pelanggan'
        }))
}

/*Data Pesanan*/

let dataPesanan = Array.isArray(dataLama.pesanan)
    ? dataLama.pesanan
    : [];


/*Nomor ID Berikutnya*/
const semuaId = [
    ...dataAdmin.akun.map(a => Number(a.id) || 0),
    ...dataPelanggan.akun.map(a => Number(a.id) || 0),
    ...dataPesanan.map(p => Number(p.id) || 0)
];
let nomorBerikut = Math.max(
    Number(dataLama.nomorBerikut) || 1,
    ...semuaId.map(id => id + 1),
    1
);

/* DataAdmin */
if (dataAdmin.akun.length === 0) {
    dataAdmin.akun.push({
        id: nomorBerikut++,
        nama: 'shinta',
        telp: '',
        alamat: '',
        sandi: '123456',
        peran: 'admin',
        status: 'Aktif'
    });
}

/* Data Pengguna Aktif*/
let perantara = 'pelanggan';
let akunAktif = null;
let metodeBayar = '';

/*Mengambil Daftar Akun*/
function daftarAkun(peran) {
    return peran === 'admin'
        ? dataAdmin.akun
        : dataPelanggan.akun;
}

/* PENYIMPANAN DATA*/
function simpanData() {
    try {
        localStorage.setItem(
            kunciSimpan,
            JSON.stringify({
                nomorBerikut,
                akun: [
                    ...dataAdmin.akun,
                    ...dataPelanggan.akun
                ],
                pesanan: dataPesanan
            })
        );
    } catch (e) {
        console.error('Gagal menyimpan data:', e);
        tampilToast('Gagal menyimpan data', true);
    }
}

/*FUNGSI BANTU*/
const aman = t => String(t ?? '').replace(/[&<>"']/g, c => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;'
}[c]));
const rupiah = n =>
    'Rp ' + Number(n).toLocaleString('id-ID');
const hariIni = () => {
    const sekarang = new Date();
    const tahun = sekarang.getFullYear();
    const bulan = String(sekarang.getMonth() + 1).padStart(2, '0');
    const hari = String(sekarang.getDate()).padStart(2, '0');
    return `${tahun}-${bulan}-${hari}`;
};
const tanggal = t => t
    ? new Date(t + 'T00:00:00').toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    })
    : '-';

/* Notifikasi*/
function tampilToast(pesan, gagal = false) {
    const toast = $('toast');
    if (!toast) return;
    toast.textContent = pesan;
    toast.className = 'toast' + (gagal ? ' gagal' : '');
    toast.style.display = 'block';
    setTimeout(() => {
        toast.style.display = 'none';
    }, 2400);
}

/*Navigasi Halaman*/
function tampilHalaman(nama) {
    Object.keys(halaman).forEach(k => {
        halaman[k].classList.toggle('aktif', k === nama);
    });
}
function tampilIsi(nama) {
    Object.keys(isi).forEach(k => {
        isi[k].classList.toggle('aktif', k === nama);
    });
    Object.keys(isi).forEach(k => {
        if (btn[k]) {
            btn[k].classList.toggle('aktif', k === nama);
        }
    });
}

/* Modal*/
function tampilModal(html) {
    $('kotakModal').innerHTML = html;
    $('latarModal').classList.add('tampil');
}
function tutupModal() {
    $('latarModal').classList.remove('tampil');
}
$('latarModal').addEventListener('click', e => {
    if (e.target.id === 'latarModal') {
        tutupModal();
    }
});

/*PESAN KONFIRMASI*/
function tanyaKonfirmasi(judul, keterangan, jikaYa) {
    tampilModal(`
        <h3>${aman(judul)}</h3>
        <p class="redup">
            ${aman(keterangan)}
        </p>
        <div class="baris">
            <button class="btn bahaya" id="btnYa">
                Ya
            </button>
            <button class="btn" id="btnBatal">
                Batal
            </button>
        </div>
    `);
    $('btnYa').onclick = () => {
        tutupModal();
        jikaYa();
    };
    $('btnBatal').onclick = tutupModal;
}

/*HALAMAN AWAL, MASUK, DAN DAFTAR*/
btn.admin.onclick = () => pilihPeran('admin');
btn.pelanggan.onclick = () => pilihPeran('pelanggan');
function pilihPeran(peran) {
    perantara = peran;
    form.masuk.reset();
    tampilHalaman('masuk');
}
btn.kembaliMasuk.onclick = () => {
    tampilHalaman('peran');
};
btn.kembaliDaftar.onclick = () => {
    tampilHalaman('masuk');
};
btn.keDaftar.onclick = e => {
    e.preventDefault();
    siapkanFormDaftar();
    tampilHalaman('daftar');
};
btn.keMasuk.onclick = e => {
    e.preventDefault();
    tampilHalaman('masuk');
};

/*FORM DAFTAR MENYESUAIKAN PERAN*/
function siapkanFormDaftar() {
    const admin = perantara === 'admin';
    form.daftar.reset();
    document.querySelectorAll('.khusus-admin-daftar')
        .forEach(el => {
            el.style.display = admin ? 'block' : 'none';
        });
    document.querySelectorAll('.khusus-pelanggan-daftar')
        .forEach(el => {
            el.style.display = admin ? 'none' : 'block';
        });
    form.sandi2Daftar.required = admin;
    form.telpDaftar.required = !admin;
    form.alamatDaftar.required = !admin;
    $('judulDaftar').textContent = admin
        ? 'Buat akun admin'
        : 'Buat akun pelanggan';
}

/* PROSES PENDAFTARAN*/
form.daftar.onsubmit = e => {
    e.preventDefault();
    const admin = perantara === 'admin';
    const nama = form.namaDaftar.value.trim();
    const sandi = form.sandiDaftar.value;
    if (!nama) {
        return tampilToast('Gagal: nama wajib diisi', true);
    }
    if (
        admin &&
        sandi !== form.sandi2Daftar.value
    ) {
        return tampilToast(
            'Gagal: verifikasi password tidak sama',
            true
        );
    }
    const semuaAkun = [
        ...dataAdmin.akun,
        ...dataPelanggan.akun
    ];
    if (
        semuaAkun.some(a =>
            a.nama.toLowerCase() === nama.toLowerCase()
        )
    ) {
        return tampilToast(
            'Gagal: nama sudah terdaftar',
            true
        );
    }
    const akunBaru = {
        id: nomorBerikut++,
        nama,
        telp: admin ? '' : form.telpDaftar.value.trim(),
        alamat: admin ? '' : form.alamatDaftar.value.trim(),
        sandi,
        peran: perantara,
        status: 'Aktif'
    };
    daftarAkun(perantara).push(akunBaru);
    simpanData();
    tampilToast('Akun berhasil dibuat, silakan login');
    tampilHalaman('masuk');
};

/*PROSES LOGIN */
form.masuk.onsubmit = e => {
    e.preventDefault();
    const kata = form.namaMasuk.value.trim().toLowerCase();
    const akun = daftarAkun(perantara).find(a =>
        a.nama.toLowerCase() === kata &&
        a.sandi === form.sandiMasuk.value
    );
    if (!akun) {
        return tampilToast(
            'Gagal: akun atau password salah',
            true
        );
    }
    if (akun.status === 'Nonaktif') {
        return tampilToast(
            'Gagal: akun Anda dinonaktifkan',
            true
        );
    }
    akunAktif = akun;
    masukAplikasi();
};

/* MASUK KE APLIKASI*/
function masukAplikasi() {
    const admin = akunAktif.peran === 'admin';
    document.querySelectorAll('.khusus-admin')
        .forEach(el => {
            el.style.display = admin ? 'block' : 'none';
        });
    document.querySelectorAll('.khusus-pelanggan')
        .forEach(el => {
            el.style.display = admin ? 'none' : 'block';
        });
    $('sapaan').textContent = 'Hi, ' + akunAktif.nama + ' Boo';
    tampilHalaman('aplikasi');
    if (admin) {
        bukaTransaksi();
    } else {
        bukaPesanansaya();
    }
    tampilToast('Selamat datang, ' + akunAktif.nama);
}
/*KELUAR DARI APLIKASI*/
btn.keluar.onclick = () => {
    tanyaKonfirmasi(
        'Apakah Anda ingin keluar dari akun ini?',
        'Setelah keluar Anda perlu login kembali.',
        () => {
            akunAktif = null;
            tampilHalaman('peran');
        }
    );
};
/* MENU SISI*/
btn.transaksi.onclick = bukaTransaksi;
btn.kelolaakun.onclick = bukaKelolaakun;
btn.pesanansaya.onclick = bukaPesanansaya;
btn.pesananbaru.onclick = bukaPesananbaru;

/*ADMIN-KELOLA PESANAN*/
function bukaTransaksi() {
    tampilIsi('transaksi');
    gambarTabelPesanan();
}
$('cariPesanan').oninput = gambarTabelPesanan;
function gambarTabelPesanan() {
    const kata = $('cariPesanan').value.toLowerCase();
    const baris = dataPesanan.filter(p =>
        p.nama.toLowerCase().includes(kata)
    );

    tabel.pesanan.innerHTML = baris.length
        ? baris.map(p => `
            <tr>
                <td>${aman(p.nama)}</td>
                <td>${aman(p.telp)}</td>
                <td>${tanggal(p.tglMasuk)}</td>
                <td>${tanggal(p.tglSelesai)}</td>
                <td>${aman(p.layanan)}</td>
                <td>${p.berat} kg</td>
                <td>${rupiah(p.total)}</td>
                <td>
                    ${aman(p.metode)}
                    ${p.sudahBayar ? ' (lunas)' : ' (belum bayar)'}
                </td>
                <td>
                    <span class="lencana ${aman(p.status)}">
                        ${aman(p.status)}
                    </span>
                </td>
                <td>
                    <button
                        class="btn kecil-btn"
                        data-struk="${p.id}"
                    >
                        Struk
                    </button>
                    <button
                        class="btn kecil-btn"
                        data-edit="${p.id}"
                    >
                        Edit
                    </button>
                    <button
                        class="btn kecil-btn bahaya"
                        data-hapus="${p.id}"
                    >
                        Hapus
                    </button>
                </td>
            </tr>
        `).join('')
        : `
            <tr>
                <td colspan="10" class="redup">
                    Belum ada pesanan.
                </td>
            </tr>
        `;
}
/*AKSI TABEL DI PESANAN*/

tabel.pesanan.onclick = e => {
    const tombol = e.target.closest('button');
    if (!tombol) return;
    const idEdit = tombol.dataset.edit;
    const idHapus = tombol.dataset.hapus;
    const idStruk = tombol.dataset.struk;
    if (idStruk) {
        tampilStruk(+idStruk);
    }
    if (idEdit) {
        editPesanan(+idEdit);
    }
    if (idHapus) {
        tanyaKonfirmasi(
            'Apakah Anda ingin menghapus data pesanan?',
            'Data yang dihapus tidak dapat dikembalikan.',
            () => {
                dataPesanan = dataPesanan.filter(
                    p => p.id !== +idHapus
                );
                simpanData();
                gambarTabelPesanan();
                tampilToast('Data berhasil dihapus');
            }
        );
    }
};
/*EDIT PESANAN*/

function editPesanan(id) {
    const p = dataPesanan.find(x => x.id === id);
    if (!p) return;

    tampilModal(`
        <h3>Edit pesanan</h3>
        <label>Nama lengkap</label>
        <input
            value="${aman(p.nama)}"
            readonly
        >
        <label>Berat (kg)</label>
        <input
            id="edBerat"
            type="number"
            min="1"
            step="0.5"
            value="${p.berat}"
            placeholder="Contoh: 4"
        >
        <label>Layanan</label>
        <select id="edLayanan">
            ${Object.keys(hargaLayanan).map(l => `
                <option
                    value="${aman(l)}"
                    ${l === p.layanan ? 'selected' : ''}
                >
                    ${aman(l)}
                </option>
            `).join('')}
        </select>
        <label>Tanggal selesai</label>
        <input
            id="edTglSelesai"
            type="date"
            value="${aman(p.tglSelesai)}"
        >
        <label>Status</label>
        <select id="edStatus">
            ${daftarStatus.map(s => `
                <option
                    value="${aman(s)}"
                    ${s === p.status ? 'selected' : ''}
                >
                    ${aman(s)}
                </option>
            `).join('')}
        </select>
        <div class="baris">
            <button class="btn utama" id="btnSimpan">
                Simpan
            </button>
            <button class="btn" id="btnBatal">
                Batal
            </button>
        </div>
    `);
    $('btnBatal').onclick = tutupModal;
    $('btnSimpan').onclick = () => {
        const berat = parseFloat($('edBerat').value);
        const tglSelesai = $('edTglSelesai').value;
        if (!(berat > 0)) {
            return tampilToast(
                'Gagal: berat tidak valid',
                true
            );
        }
        if (!tglSelesai) {
            return tampilToast(
                'Gagal: tanggal selesai wajib diisi',
                true
            );
        }
        if (tglSelesai < p.tglMasuk) {
            return tampilToast(
                'Gagal: tanggal selesai lebih awal dari tanggal masuk',
                true
            );
        }
        p.berat = berat;
        p.layanan = $('edLayanan').value;
        p.tglSelesai = tglSelesai;
        p.status = $('edStatus').value;
        p.total = berat * hargaLayanan[p.layanan];
        if (p.status === 'Diambil') {
            p.sudahBayar = true;
        }
        simpanData();
        tutupModal();
        gambarTabelPesanan();
        tampilToast('Data berhasil disimpan');
    };
}
/* ADMIN-KELOLA AKUN PELANGGAN*/

function bukaKelolaakun() {
    tampilIsi('kelolaakun');
    gambarTabelAkun();
}
$('cariAkun').oninput = gambarTabelAkun;

function gambarTabelAkun() {
    const kata = $('cariAkun').value.toLowerCase();
    const baris = dataPelanggan.akun.filter(a =>
        a.nama.toLowerCase().includes(kata)
    );

    tabel.akun.innerHTML = baris.length
        ? baris.map(a => `
            <tr>
                <td>${aman(a.nama)}</td>
                <td>${aman(a.telp)}</td>
                <td>
                    <span class="lencana ${aman(a.status)}">
                        ${aman(a.status)}
                    </span>
                </td>
                <td>
                    <button
                        class="btn kecil-btn"
                        data-status="${a.id}"
                    >
                        ${a.status === 'Aktif'
                            ? 'Nonaktifkan'
                            : 'Aktifkan'}
                    </button>
                    <button
                        class="btn kecil-btn"
                        data-edit="${a.id}"
                    >
                        Edit
                    </button>
                    <button
                        class="btn kecil-btn bahaya"
                        data-hapus="${a.id}"
                    >
                        Hapus
                    </button>
                </td>
            </tr>
        `).join('')
        : `
            <tr>
                <td colspan="4" class="redup">
                    Belum ada akun pelanggan.
                </td>
            </tr>
        `;
}
/* AKSI TABEL DI AKUN PELANGGAN*/

tabel.akun.onclick = e => {
    const tombol = e.target.closest('button');
    if (!tombol) return;
    const { edit, hapus, status } = tombol.dataset;
    if (status) {
        const a = dataPelanggan.akun.find(
            x => x.id === +status
        );
        if (!a) return;

        a.status = a.status === 'Aktif'
            ? 'Nonaktif'
            : 'Aktif';
        simpanData();
        gambarTabelAkun();
        tampilToast('Status akun diperbarui');
    }

    if (edit) {
        editAkun(+edit);
    }

    if (hapus) {
        tanyaKonfirmasi(
            'Apakah Anda ingin menghapus akun pelanggan?',
            'Pesanan milik akun ini ikut terhapus.',
            () => {
                dataPelanggan.akun = dataPelanggan.akun.filter(
                    a => a.id !== +hapus
                );
                dataPesanan = dataPesanan.filter(
                    p => p.idAkun !== +hapus
                );
                simpanData();
                gambarTabelAkun();
                tampilToast('Data berhasil dihapus');
            }
        );
    }
};
/*EDIT AKUN PELANGGAN*/

function editAkun(id) {
    const a = dataPelanggan.akun.find(x => x.id === id);
    if (!a) return;
    tampilModal(`
        <h3>Edit akun pelanggan</h3>
        <label>Nama lengkap</label>
        <input
            id="edNama"
            value="${aman(a.nama)}"
            placeholder="Contoh: Sari Wulandari"
        >
        <label>Password baru (kosongkan jika tidak diubah)</label>
        <input
            id="edSandi"
            type="password"
            placeholder="Kosongkan jika tidak diubah"
        >
        <label>Verifikasi password</label>
        <input
            id="edSandi2"
            type="password"
            placeholder="Ulangi password baru"
        >
        <div class="baris">
            <button class="btn utama" id="btnSimpan">
                Simpan
            </button>
            <button class="btn" id="btnBatal">
                Batal
            </button>
        </div>
    `);
    $('btnBatal').onclick = tutupModal;
    $('btnSimpan').onclick = () => {
        const nama = $('edNama').value.trim();
        const sandi = $('edSandi').value;
        if (!nama) {
            return tampilToast(
                'Gagal: nama wajib diisi',
                true
            );
        }
        const namaSudahAda = [
            ...dataAdmin.akun,
            ...dataPelanggan.akun
        ].some(x =>
            x.id !== a.id &&
            x.nama.toLowerCase() === nama.toLowerCase()
        );
        if (namaSudahAda) {
            return tampilToast(
                'Gagal: nama sudah digunakan',
                true
            );
        }
        if (sandi && sandi !== $('edSandi2').value) {
            return tampilToast(
                'Gagal: verifikasi password tidak sama',
                true
            );
        }
        a.nama = nama;
        if (sandi) {
            a.sandi = sandi;
        }
        dataPesanan
            .filter(p => p.idAkun === a.id)
            .forEach(p => {
                p.nama = nama;
            });
        simpanData();
        tutupModal();
        gambarTabelAkun();
        tampilToast('Data berhasil disimpan');
    };
}
/*PELANGGAN-PESANAN SAYA*/
function bukaPesanansaya() {
    tampilIsi('pesanansaya');

    const milikSaya = dataPesanan
        .filter(p => p.idAkun === akunAktif.id)
        .reverse();

    tabel.pesananSaya.innerHTML = milikSaya.length
        ? milikSaya.map(p => `
            <div class="kartu ${
                p.status === 'Diproses'
                    ? 'proses'
                    : (
                        p.status === 'Selesai' ||
                        p.status === 'Diambil'
                    )
                        ? 'selesai'
                        : ''
            }">
                <div class="atas">
                    <span>
                        ${aman(p.layanan)}, ${p.berat} kg
                    </span>
                    <span class="lencana ${aman(p.status)}">
                        ${aman(p.status)}
                    </span>
                </div>
                <p class="redup kecil">
                    Masuk ${tanggal(p.tglMasuk)},
                    estimasi selesai ${tanggal(p.tglSelesai)}
                </p>
                <b>${rupiah(p.total)}</b>
                <span class="redup kecil">
                    (${aman(p.metode)}${p.sudahBayar
                        ? ', lunas'
                        : ', belum bayar'})
                </span>
                <div>
                    <button
                        class="btn kecil-btn"
                        data-struk="${p.id}"
                    >
                        Cetak struk
                    </button>
                </div>
            </div>
        `).join('')
        : `
            <p class="redup">
                Belum ada pesanan.
                Buat pesanan lewat menu Pesanan baru.
            </p>
        `;
}
/*PELANGGAN: PESANAN BARU*/

function bukaPesananbaru() {
    tampilIsi('pesananbaru');
    form.pesanan.reset();
    form.layanan.innerHTML = Object.keys(hargaLayanan)
        .map(l => `
            <option value="${aman(l)}">
                ${aman(l)}
            </option>
        `)
        .join('');
    form.nama.value = akunAktif.nama;
    form.telp.value = akunAktif.telp;
    form.alamat.value = akunAktif.alamat;
    form.tglMasuk.value = hariIni();
    metodeBayar = '';
    btn.cash.classList.remove('aktif');
    btn.qris.classList.remove('aktif');
    hitungTotal();
}
/*HITUNG TOTAL HARGA*/
function hitungTotal() {
    const harga = hargaLayanan[form.layanan.value] || 0;
    form.harga.value = rupiah(harga) + ' / kg';
    form.total.value = rupiah(
        (parseFloat(form.berat.value) || 0) * harga
    );
}
form.layanan.onchange = hitungTotal;
form.berat.oninput = hitungTotal;
/* PILIH METODE PEMBAYARAN*/
btn.cash.onclick = () => pilihMetode('Cash');
btn.qris.onclick = () => pilihMetode('QRIS');
function pilihMetode(metode) {
    metodeBayar = metode;
    btn.cash.classList.toggle(
        'aktif',
        metode === 'Cash'
    );
    btn.qris.classList.toggle(
        'aktif',
        metode === 'QRIS'
    );
}
/*PROSES PEMESANAN*/

form.pesanan.onsubmit = e => {
    e.preventDefault();
    const berat = parseFloat(form.berat.value);
    if (!(berat > 0)) {
        return tampilToast(
            'Gagal: isi berat cucian',
            true
        );
    }
    if (!form.tglSelesai.value) {
        return tampilToast(
            'Gagal: tanggal selesai wajib diisi',
            true
        );
    }
    if (form.tglSelesai.value < form.tglMasuk.value) {
        return tampilToast(
            'Gagal: tanggal selesai lebih awal dari tanggal masuk',
            true
        );
    }
    if (!metodeBayar) {
        return tampilToast(
            'Gagal: pilih metode pembayaran',
            true
        );
    }

    const total = berat * hargaLayanan[form.layanan.value];
    tampilModal(`
        <h3>Ringkasan pesanan</h3>
\        <div class="ringkas">
    ]            <p>
                <span>Metode pembayaran</span>
                <b>${aman(metodeBayar)}</b>
            </p>
            <p>
                <span>Layanan</span>
                <b>${aman(form.layanan.value)}</b>
            </p>
            <p>
                <span>Berat</span>
                <b>${berat} kg</b>
            </p>
            <p>
                <span>Harga</span>
                <b>
                    ${rupiah(hargaLayanan[form.layanan.value])} / kg
                </b>
            </p>
            <p>
                <span>Total</span>
                <b>${rupiah(total)}</b>
            </p>
        </div>
        <div class="baris">
            <button class="btn utama" id="btnLanjut">
                Lanjut bayar
            </button>
\            <button class="btn" id="btnBatal">
                Batal
            </button>
        </div>
    `);
    $('btnBatal').onclick = tutupModal;
    $('btnLanjut').onclick = () => {
        if (metodeBayar === 'Cash') {
            tampilBayarCash(total);
        } else {
            tampilBayarQris(total);
        }
    };
};
/*CASH*/

function tampilBayarCash(total) {
    tampilModal(`
        <h3>
            Silakan pergi ke kasir untuk melakukan pembayaran
        </h3>
        <p class="redup">
            Tunjukkan pesanan Anda.
            Total ${rupiah(total)}.
        </p>
        <div class="baris">
            <button class="btn bahaya" id="btnOk">
                Ok
            </button>
        </div>
    `);

    $('btnOk').onclick = () => {
        buatPesanan(total, false);
    };
}
/*QRIS*/

function tampilBayarQris(total) {
    let kotak = '';

    for (let i = 0; i < 441; i++) {
        kotak += `
            <i class="${
                (
                    i * 7 +
                    (i % 5) * 3 +
                    Math.floor(i / 21)
                ) % 3 === 0
                    ? 'hitam'
                    : ''
            }"></i>
        `;
    }

    tampilModal(`
        <h3>QRIS</h3>
        <div class="qr">
            ${kotak}
        </div>
        <p class="redup" style="text-align:center">
            Scan untuk membayar ${rupiah(total)}
            (contoh tampilan)
        </p>
        <div class="baris">
            <button class="btn utama" id="btnSudahBayar">
                Sudah bayar
            </button>
        </div>
    `);
    $('btnSudahBayar').onclick = () => {
        buatPesanan(total, true);
    };
}

/* SIMPAN PESANAN*/
function buatPesanan(total, sudahBayar) {
    const pesananBaru = {
        id: nomorBerikut++,
        idAkun: akunAktif.id,
        nama: akunAktif.nama,
        telp: form.telp.value.trim(),
        alamat: form.alamat.value.trim(),
        tglMasuk: form.tglMasuk.value,
        tglSelesai: form.tglSelesai.value,
        layanan: form.layanan.value,
        berat: parseFloat(form.berat.value),
        total,
        metode: metodeBayar,
        sudahBayar,
        status: 'Menunggu'
    };

    dataPesanan.push(pesananBaru);
    akunAktif.telp = form.telp.value.trim();
    akunAktif.alamat = form.alamat.value.trim();
    simpanData();
    tutupModal();
    bukaPesanansaya();
    tampilToast('Pesanan berhasil dibuat');
}
/* BTN CETAK STRUK*/
tabel.pesananSaya.onclick = e => {
    const tombol = e.target.closest('button');
    if (!tombol) return;

    if (tombol.dataset.struk) {
        tampilStruk(+tombol.dataset.struk);
    }
};
/*   CETAK STRUK*/
function tampilStruk(id) {
    const p = dataPesanan.find(x => x.id === id);
    if (!p) return;
    const nomor = 'LB-' + String(p.id).padStart(4, '0');
    const baris = (nama, nilai) => `
        <p>
            <span>${aman(nama)}</span>
            <span>${aman(nilai)}</span>
        </p>
    `;
    tampilModal(`
        <div class="struk" id="areaStruk">
            <div class="struk-judul">
                LaundryBoo
            </div>
            <div class="struk-tengah">
                Struk Pesanan
            </div>
            <hr>
            ${baris('No.', nomor)}
            ${baris(
                'Dicetak',
                new Date().toLocaleString('id-ID')
            )}
            ${baris('Pelanggan', p.nama)}
            ${baris('No. telp', p.telp || '-')}
            <hr>
            ${baris('Layanan', p.layanan)}
            ${baris('Berat', p.berat + ' kg')}
            ${baris(
                'Harga/kg',
                rupiah(hargaLayanan[p.layanan])
            )}
            ${baris('Tgl masuk', tanggal(p.tglMasuk))}
            ${baris('Tgl selesai', tanggal(p.tglSelesai))}
            <hr>
            <p class="struk-total">
                <span>TOTAL</span>
                <span>${rupiah(p.total)}</span>
            </p>
            ${baris(
                'Bayar',
                p.metode + (
                    p.sudahBayar
                        ? ' (lunas)'
                        : ' (belum bayar)'
                )
            )}
            ${baris('Status', p.status)}
            <hr>
            <div class="struk-tengah">
                Terima kasih sudah mencuci di LaundryBoo
            </div>
        </div>
        <div class="baris">
            <button class="btn utama" id="btnCetak">
                Cetak
            </button>

            <button class="btn" id="btnBatal">
                Tutup
            </button>
        </div>
    `);
    $('btnBatal').onclick = tutupModal;
    $('btnCetak').onclick = () => {
        window.print();
    };
};
simpanData();