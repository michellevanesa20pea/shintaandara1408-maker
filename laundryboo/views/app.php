
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryBoo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Patrick+Hand&display=swap">
    <link rel="stylesheet" href="assets/laundry.css">
    <link rel="stylesheet" href="assets/layout.css">
</head>

<body>
    <!--PILIH PERAN -->
    <section id="halamanPeran" class="halaman aktif tengah">
        <div class="kotak">
            <h1 class="logo">
                <svg class="ikon-logo" viewBox="0 0 48 48" aria-hidden="true">
                    <rect x="8" y="5" width="32" height="38" rx="5"
                        fill="none" stroke="currentColor" stroke-width="3"/>
                    <circle cx="15" cy="12" r="1.8" fill="currentColor"/>
                    <circle cx="22" cy="12" r="1.8" fill="currentColor"/>
                    <circle cx="24" cy="27" r="10"
                        fill="none" stroke="currentColor" stroke-width="3"/>
                    <path d="M18 27c2-3 4 3 6 0s4 3 6 0"
                        fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                LaundryBoo
            </h1>
            <p class="redup">Masuk sebagai siapa?</p>
            <button id="btnAdmin" class="btn utama">Admin</button>
            <button id="btnpelanggan" class="btn">Pelanggan</button>
        </div>
    </section>
    <!--HALAMAN LOGIN -->
    <section id="halamanMasuk" class="halaman tengah">
        <form id="formMasuk" class="kotak">
            <button
                type="button"
                id="btnKembaliMasuk"
                class="btn-kembali"
                aria-label="Kembali">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                    <path d="M15 5l-7 7 7 7"
                        fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round"/>
                </svg>
            </button>
            <h1 class="logo">
                <svg class="ikon-logo" viewBox="0 0 48 48" aria-hidden="true">
                    <rect x="8" y="5" width="32" height="38" rx="5"
                        fill="none" stroke="currentColor" stroke-width="3"/>
                    <circle cx="15" cy="12" r="1.8" fill="currentColor"/>
                    <circle cx="22" cy="12" r="1.8" fill="currentColor"/>
                    <circle cx="24" cy="27" r="10"
                        fill="none" stroke="currentColor" stroke-width="3"/>
                    <path d="M18 27c2-3 4 3 6 0s4 3 6 0"
                        fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                LaundryBoo
            </h1>
            <p id="judulMasuk" class="redup">
                Login untuk melanjutkan ke halaman selanjutnya
            </p>
            <label>Nama lengkap</label>
            <input
                id="inputNamaMasuk"
                placeholder="michell/shinta"
                required>
            <label>Password</label>
            <input
                id="inputSandiMasuk"
                type="password"
                placeholder="Masukkan password"
                required>
            <button id="btnMasuk" class="btn utama" type="submit">
                Login
            </button>
            <p class="redup kecil">
                Belum punya akun?
                <a href="#" id="btnKeDaftar">Daftar</a>
            </p>
        </form>
    </section>

    <!-- HALAMAN DAFTAR -->
    <section id="halamanDaftar" class="halaman tengah">
        <form id="formDaftar" class="kotak">
            <button
                type="button"
                id="btnKembaliDaftar"
                class="btn-kembali"
                aria-label="Kembali">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                    <path d="M15 5l-7 7 7 7"
                        fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round"/>
                </svg>
            </button>
            <h1 class="logo">
                <svg class="ikon-logo" viewBox="0 0 48 48" aria-hidden="true">
                    <rect x="8" y="5" width="32" height="38" rx="5"
                        fill="none" stroke="currentColor" stroke-width="3"/>
                    <circle cx="15" cy="12" r="1.8" fill="currentColor"/>
                    <circle cx="22" cy="12" r="1.8" fill="currentColor"/>
                    <circle cx="24" cy="27" r="10"
                        fill="none" stroke="currentColor" stroke-width="3"/>
                    <path d="M18 27c2-3 4 3 6 0s4 3 6 0"
                        fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                <span id="judulDaftar">Buat akun baru</span>
            </h1>
            <label>Nama lengkap</label>
            <input
                id="inputNamaDaftar"
                placeholder="michell/shinta"
                required>
            <div class="khusus-pelanggan-daftar">
                <label>No. telepon</label>
                <input
                    id="inputTelpDaftar"
                    inputmode="numeric"
                    placeholder="Contoh: 081234567890">
            </div>
            <div class="khusus-pelanggan-daftar">
                <label>Alamat</label>
                <input
                    id="inputAlamatDaftar"
                    placeholder="jl. Gunung Payung">
            </div>
            <label>Password</label>
            <input
                id="inputSandiDaftar"
                type="password"
                minlength="6"
                placeholder="123456"
                required>
            <div class="khusus-admin-daftar">
                <label>Verifikasi password</label>
                <input
                    id="inputSandi2Daftar"
                    type="password"
                    minlength="6"
                    placeholder="Ulangi password">
            </div>
            <button id="btnDaftar" class="btn utama" type="submit">
                Simpan
            </button>
            <p class="redup kecil">
                <a href="#" id="btnKeMasuk">Sudah punya akun? Login</a>
            </p>
        </form>
    </section>

    <!--HALAMAN APLIKASI -->
    <section id="halamanAplikasi" class="halaman">
        <div class="tata">
            <!-- logo-->
            <aside class="sisi">
                <p class="logo kecil-logo">
                    <svg class="ikon-logo" viewBox="0 0 48 48" aria-hidden="true">
                        <rect x="8" y="5" width="32" height="38" rx="5"
                            fill="none" stroke="currentColor" stroke-width="3"/>
                        <circle cx="15" cy="12" r="1.8" fill="currentColor"/>
                        <circle cx="22" cy="12" r="1.8" fill="currentColor"/>
                        <circle cx="24" cy="27" r="10"
                            fill="none" stroke="currentColor" stroke-width="3"/>
                        <path d="M18 27c2-3 4 3 6 0s4 3 6 0"
                            fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                    LaundryBoo
                </p>
                <p id="sapaan" class="sapaan">Hi, </p>
                <!-- MENU-ADMIN -->
                <button id="btnTransaksi" class="menu khusus-admin">
                    Pesanan
                </button>
                <button id="btnKelolaakun" class="menu khusus-admin">
                    Kelola akun
                </button>

                <!-- MENU-pelanggan -->
                <button id="btnPesanansaya" class="menu khusus-pelanggan">
                    Pesanan saya
                </button>
                <button id="btnPesananbaru" class="menu khusus-pelanggan">
                    Pesanan baru
                </button>
                <button id="btnKeluar" class="menu keluar">
                    Keluar
                </button>
            </aside>

            <main class="konten">
                <!-- ADMIN-DATA PESANAN -->
                <div id="isiTransaksi" class="isi">
                    <h2>Data pesanan</h2>
                    <input
                        id="cariPesanan"
                        class="cari"
                        placeholder="Cari nama pelanggan...">
                    <div class="gulir">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama lengkap</th>
                                    <th>No. telp</th>
                                    <th>Tgl masuk</th>
                                    <th>Tgl selesai</th>
                                    <th>Layanan</th>
                                    <th>Berat</th>
                                    <th>Total</th>
                                    <th>Bayar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabelPesanan"></tbody>
                        </table>
                    </div>
                </div>
                <!-- ADMIN-KELOLA AKUN -->
                <div id="isiKelolaakun" class="isi">
                    <h2>Kelola akun</h2>
                    <input
                        id="cariAkun"
                        class="cari"
                        placeholder="Cari nama...">
                    <div class="gulir">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama lengkap</th>
                                    <th>No. telp</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabelAkun"></tbody>
                        </table>
                    </div>
                </div>
                <!-- pelanggan-PESANAN SAYA -->
                <div id="isiPesanansaya" class="isi">
                    <h2>Pesanan saya</h2>
                    <div id="daftarPesananSaya"></div>
                </div>
                <!-- pelanggan-PESANAN BARU -->
                <div id="isiPesananbaru" class="isi">
                    <h2>Pesanan baru</h2>
                    <form id="formPesanan" class="formgrid">
                        <div>
                            <label>Nama lengkap</label>
                            <input id="pbNama" readonly>
                        </div>
                        <div>
                            <label>No. telepon</label>
                            <input
                                id="pbTelp"
                                placeholder="Contoh: 081234567890"
                                required
                            >
                        </div>

                        <div class="lebar">
                            <label>Alamat</label>
                            <input
                                id="pbAlamat"
                                placeholder="Contoh: Jl. Melati No. 5, Semarang"
                                required>
                        </div>
                        <div>
                            <label>Tanggal masuk</label>
                            <input id="pbTglMasuk" type="date" required>
                        </div>
                        <div>
                            <label>Tanggal selesai</label>
                            <input id="pbTglSelesai" type="date" required>
                        </div>
                        <div>
                            <label>Layanan</label>
                            <select id="pbLayanan"></select>
                        </div>
                        <div>
                            <label>Berat (kg)</label>
                            <input
                                id="pbBerat"
                                type="number"
                                min="1"
                                step="0.5"
                                placeholder="Contoh: 4"
                                required>
                        </div>
                        <div>
                            <label>Harga per kg</label>
                            <input id="pbHarga" readonly>
                        </div>
                        <div>
                            <label>Total</label>
                            <input id="pbTotal" readonly>
                        </div>
                        <div class="lebar">
                            <label>Metode pembayaran</label>
                            <div class="baris">
                                <button type="button" id="btnCash" class="btn">
                                    Cash
                                </button>
                                <button type="button" id="btnQris" class="btn">
                                    Qris
                                </button>
                            </div>
                        </div>
                        <div class="lebar">
                            <button id="btnPesan" class="btn utama" type="submit">
                                Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </section>
    <div id="latarModal" class="latar">
        <div id="kotakModal" class="modal"></div>
    </div>
    <div id="toast" class="toast" role="status"></div>
    <script src="assets/laundry.js"></script>
</body>
</html>
