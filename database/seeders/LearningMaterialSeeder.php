<?php

namespace Database\Seeders;

use App\Models\LearningMaterial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LearningMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            ['legitimate', 'all', 'Membedakan Pesan Aman dan Berbahaya',
                'Belajar tetap tenang: tidak semua pesan dari nomor tak dikenal adalah penipuan.',
                "## Kenapa ini penting
Banyak orang menjadi terlalu panik sehingga memblokir pesan yang sebenarnya wajar, atau sebaliknya, menuruti pesan berbahaya karena tampak meyakinkan. Kuncinya adalah mengenali pola, bukan sekadar curiga pada semua hal.

## Ciri pesan yang relatif aman
- Hanya memberi informasi (notifikasi pengiriman, struk, pengingat jadwal).
- Tidak meminta OTP, PIN, password, atau data kartu.
- Tidak ada tautan yang menyuruh login.
- Tidak memaksa bertindak cepat atau menakut-nakuti.

## Contoh nyata
Contoh: \"Pesanan #1234 sudah dikirim via JNE, resi 0098123456.\" — ini wajar karena hanya info, tanpa permintaan data.
Contoh: \"Kode OTP Anda 882190, jangan beri ke siapa pun.\" — SMS ini aman; yang berbahaya adalah jika ADA orang lain meminta kodenya.

## Langkah menyikapi
1. Baca pesan dengan tenang, jangan langsung panik.
2. Cek: apakah ada permintaan data rahasia atau tautan mendesak?
3. Jika hanya informasi, cukup pantau lewat aplikasi resmi.
4. Jika ragu, hubungi lembaga lewat kontak resmi yang kamu cari sendiri.

Ingat: Aman bukan berarti lengah. Verifikasi mandiri tetap kebiasaan terbaik."],

            ['phishing_link', 'all', 'Mengenali Link dan Domain Palsu',
                'Tautan palsu dibuat semirip mungkin dengan situs resmi untuk mencuri data Anda.',
                "## Apa itu phishing link
Phishing adalah upaya memancing korban membuka halaman palsu agar memasukkan data login, OTP, atau data kartu. Halaman dibuat menyerupai aslinya.

## Ciri tautan berbahaya
- Domain mirip tapi tidak sama persis dengan aslinya.
- Memakai akhiran tidak lazim untuk lembaga resmi seperti .xyz, .online, .id acak.
- Disembunyikan dengan pemendek tautan (bit.ly, cutt.ly).
- Begitu dibuka langsung meminta login beserta OTP.

## Contoh nyata
Contoh: ov0-cek.id meniru ovo.id (angka 0 menggantikan huruf o).
Contoh: tagihan-online.xyz mengaku penyedia listrik padahal domain resmi berakhiran .co.id.

## Langkah aman
1. Jangan klik tautan dari pesan yang tidak kamu minta.
2. Buka layanan lewat aplikasi resmi, atau ketik alamatnya sendiri di browser.
3. Periksa nama domain huruf demi huruf sebelum login.
4. Curigai pesan yang memaksa kamu cepat-cepat.

Ingat: Bank dan e-wallet tidak pernah meminta login lewat tautan yang dikirim via SMS/chat."],

            ['fake_giveaway', 'all', 'Waspada Hadiah dan Undian Palsu',
                'Hadiah besar yang datang tiba-tiba hampir selalu umpan penipuan.',
                "## Pola penipuan hadiah
Penipu memanfaatkan rasa senang. Korban diminta klik tautan, isi data, atau bayar \"pajak/biaya administrasi\" yang sebenarnya masuk kantong penipu.

## Ciri hadiah palsu
- Menang padahal tidak pernah ikut undian.
- Diminta klik tautan atau isi data pribadi untuk klaim.
- Diminta membayar biaya di muka.
- Memakai nama besar tanpa kanal resmi.

## Contoh nyata
Contoh: \"Selamat menang Rp10.000.000, balas dengan nama & nomor rekening.\"
Contoh: \"Transfer pajak hadiah Rp500.000 dulu agar mobil cair.\"

## Langkah aman
1. Abaikan dan jangan klik tautan apa pun.
2. Ingat: hadiah resmi tidak pernah meminta bayar di muka.
3. Cek pengumuman hanya di akun/website resmi penyelenggara.

Ingat: Kalau harus bayar untuk \"menang\", itu bukan hadiah, itu penipuan."],

            ['otp_fraud', 'all', 'OTP, PIN, dan CVV Itu Rahasia',
                'Kode ini adalah kunci akun Anda. Siapa pun yang memintanya patut dicurigai.',
                "## Mengapa kode ini berbahaya bila bocor
OTP, PIN, dan CVV memberi akses langsung ke uang dan akun Anda. Sekali diberikan, penipu bisa menguras saldo dalam hitungan detik.

## Aturan utama
- Petugas bank, e-wallet, atau ekspedisi tidak pernah meminta OTP/PIN/CVV.
- OTP hanya dipakai oleh Anda sendiri saat login atau transaksi.
- Jangan sebutkan kode lewat telepon, chat, atau formulir mana pun.

## Modus yang sering dipakai
Contoh: \"Saya admin bank, sebutkan OTP demi keamanan akun Anda.\"
Contoh: \"Paket gagal kirim, konfirmasi dengan kode verifikasi yang kami kirim.\"

## Langkah aman
1. Akhiri percakapan begitu ada yang meminta kode.
2. Jangan terpancing rasa takut atau terburu-buru.
3. Hubungi lembaga lewat call center resmi bila perlu memastikan.

Ingat: Tidak ada alasan sah bagi siapa pun untuk meminta OTP Anda."],

            ['password_security', 'all', 'Membuat Password yang Kuat',
                'Password lemah membuat akun mudah dibobol meski tanpa penipuan.',
                "## Kenapa password penting
Password adalah pertahanan pertama. Jika lemah atau dipakai berulang, satu kebocoran bisa membuka semua akun Anda.

## Yang harus dihindari
- Password umum seperti 123456, qwerty, password.
- Tanggal lahir, nama sendiri, atau nomor HP.
- Password sama untuk banyak akun.

## Yang disarankan
- Minimal 12 karakter, campur huruf besar/kecil, angka, dan simbol.
- Berbeda untuk akun penting (email, m-banking, e-wallet).
- Aktifkan verifikasi dua langkah bila tersedia.

## Contoh
Contoh: \"budi1990\" lemah — ada nama dan tahun lahir.
Contoh: \"K7!mfa-2x9Q\" kuat — acak, panjang, beragam karakter.

## Langkah aman
1. Ganti password penting yang masih lemah sekarang.
2. Gunakan password manager agar tidak perlu menghafal.
3. Jangan pernah membagikan password \"untuk verifikasi\".

Ingat: Password yang baik adalah yang panjang, acak, dan unik per akun."],

            ['marketplace_scam', 'all', 'Belanja Online dengan Aman',
                'Sistem pembayaran marketplace melindungi pembeli, jangan keluar darinya.',
                "## Bagaimana penipuan terjadi
Penipu membujuk transaksi di luar aplikasi agar tidak ada perlindungan dana, lalu kabur setelah dibayar.

## Ciri yang patut dicurigai
- Diminta transfer langsung ke rekening pribadi.
- Harga jauh di bawah pasar dengan alasan \"stok terakhir\".
- Penjual mendesak segera bayar.
- \"Admin\" minta login lewat tautan.

## Contoh nyata
Contoh: \"Biar murah jangan checkout di aplikasi, transfer ke rekening saya.\"
Contoh: \"HP Rp1,5 juta (normal Rp15 juta), bayar sekarang atau hangus.\"

## Langkah aman
1. Selalu checkout dan bayar di dalam aplikasi resmi.
2. Tolak ajakan transfer ke rekening pribadi.
3. Periksa rating, ulasan, dan riwayat toko.
4. Curigai harga yang tidak masuk akal.

Ingat: Perlindungan dana hanya berlaku bila transaksi di dalam aplikasi."],

            ['apk_malware', 'all', 'Bahaya File APK dari Chat',
                'Satu file APK kiriman bisa menyadap SMS, OTP, dan menguras saldo.',
                "## Kenapa APK kiriman berbahaya
Aplikasi di luar toko resmi bisa diberi izin membaca SMS (termasuk OTP), kontak, dan menjalankan transaksi diam-diam.

## Penyamaran yang umum
- Undangan, resi, atau foto yang ternyata berakhiran .apk.
- \"Aplikasi cek resi\" yang harus dipasang di luar Play Store.

## Contoh nyata
Contoh: file \"undangan_pernikahan.apk\".
Contoh: file \"bukti_transfer.apk\" — bukti seharusnya .jpg, bukan aplikasi.

## Langkah aman
1. Jangan unduh file APK dari chat siapa pun.
2. Pasang aplikasi hanya dari Play Store / App Store.
3. Periksa izin aplikasi sebelum menyetujui.
4. Aktifkan proteksi bawaan ponsel (Play Protect).

Ingat: Dokumen asli tidak pernah berbentuk aplikasi (.apk)."],

            ['pinjol_ilegal', 'all', 'Mengenali Pinjol Ilegal',
                'Pinjol ilegal menjebak dengan iming-iming cepat lalu meneror peminjam.',
                "## Cara kerja pinjol ilegal
Menawarkan pencairan instan, lalu meminta akses kontak/galeri untuk meneror Anda dan orang terdekat bila telat bayar.

## Ciri-ciri
- Tidak terdaftar/berizin di OJK.
- Cair instan tanpa BI checking lewat tautan atau APK.
- Minta izin akses kontak, galeri, lokasi.
- Bunga sangat tinggi dengan tenor sangat pendek.

## Contoh nyata
Contoh: \"Cair 5 menit tanpa jaminan, daftar di dana-kilat.online.\"
Contoh: \"Pasang aplikasi, cukup izinkan akses kontak dan galeri.\"

## Jika sudah terlanjur diteror
1. Jangan panik dan jangan transfer ke rekening pribadi.
2. Cek legalitas layanan di kanal resmi OJK.
3. Laporkan ke Satgas/OJK dan simpan bukti.

Ingat: Layanan resmi tidak pernah meminta akses kontak sebagai syarat pinjaman."],

            ['job_scam', 'all', 'Lowongan Kerja Palsu',
                'Rekrutmen palsu meminta uang atau data sensitif di awal proses.',
                "## Pola umum
Korban dijanjikan gaji besar dengan proses mudah, lalu diminta membayar \"biaya\" atau menyerahkan data sensitif.

## Ciri-ciri
- Diminta biaya pendaftaran, seragam, atau pelatihan di muka.
- Email dari alamat pribadi, bukan domain perusahaan.
- Diterima sangat cepat tanpa wawancara serius.
- Tugas awal berupa top up atau beli produk dulu.

## Contoh nyata
Contoh: \"Transfer Rp250.000 untuk aktivasi akun kerja.\"
Contoh: \"Top up Rp1 juta, nanti dikembalikan plus komisi.\"

## Langkah aman
1. Tolak setiap permintaan biaya dalam rekrutmen.
2. Verifikasi lowongan di situs karier resmi perusahaan.
3. Jangan kirim KTP/rekening ke kontak pribadi.

Ingat: Perusahaan yang sah membayar Anda, bukan sebaliknya."],

            ['qris_scam', 'all', 'QRIS dan Bukti Transfer Palsu',
                'Bukti transfer mudah diedit dan QRIS bisa diganti penipu.',
                "## Dua modus utama
Pertama, bukti transfer palsu agar penjual mengirim barang. Kedua, QRIS yang penerimanya bukan toko sebenarnya.

## Saat menerima pembayaran
- Jangan percaya foto bukti transfer.
- Pastikan dana benar masuk lewat mutasi di aplikasi resmi.

## Saat membayar dengan QRIS
- Pastikan nama penerima sama dengan nama toko.
- Nama berbeda berarti waspada, jangan lanjut.

## Contoh nyata
Contoh: \"Sudah saya transfer (bukti_tf.jpg), tolong kirim barangnya.\"
Contoh: \"Maaf transfer kelebihan Rp500rb, tolong kembalikan selisihnya.\"

## Langkah aman
1. Cek mutasi rekening resmi sebelum kirim barang/uang.
2. Jangan kembalikan \"kelebihan\" sebelum dana benar masuk.
3. Verifikasi nama penerima QRIS sebelum bayar.

Ingat: Bukti transfer berupa gambar bukan jaminan dana sudah masuk."],
        ];

        foreach ($materials as $m) {
            LearningMaterial::updateOrCreate(
                ['slug' => Str::slug($m[2])],
                [
                    'category' => $m[0],
                    'target_level' => $m[1],
                    'title' => $m[2],
                    'summary' => $m[3],
                    'content' => $m[4],
                    'is_active' => true,
                ]
            );
        }
    }
}
