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
                'Tidak semua pesan dari nomor tak dikenal itu penipuan. Kenali ciri pesan yang relatif aman.',
                "Ciri pesan yang relatif aman:
- Tidak meminta OTP, PIN, CVV, atau password.
- Tidak ada link mencurigakan atau perintah login mendadak.
- Tidak memaksa kamu bertindak cepat atau menakut-nakuti.
- Berasal dari kanal yang bisa kamu verifikasi.

Yang tetap perlu diwaspadai:
- Pesan OTP yang masuk justru menandakan ada yang mencoba masuk ke akunmu. Jangan berikan kodenya ke siapa pun.
- Walau pesan terlihat aman, kalau ragu tetap cek lewat aplikasi resmi.

Kesimpulan:
- Aman bukan berarti lengah. Verifikasi tetap kebiasaan terbaik."],

            ['phishing_link', 'all', 'Mengenali Link dan Domain Palsu',
                'Link phishing dibuat mirip situs resmi untuk mencuri data kamu.',
                "Ciri link berbahaya:
- Domain mirip tapi tidak sama persis, misalnya ov0-cek.id padahal aslinya ovo.id.
- Memakai akhiran aneh seperti .xyz atau .online untuk lembaga resmi.
- Disembunyikan dengan pemendek link seperti bit.ly.
- Halaman langsung meminta login beserta OTP.

Cara aman:
1. Jangan klik link dari pesan yang tidak kamu minta.
2. Buka layanan lewat aplikasi resmi atau ketik alamatnya sendiri.
3. Periksa nama domain huruf per huruf sebelum login.
4. Curigai pesan yang memaksa kamu cepat-cepat."],

            ['fake_giveaway', 'all', 'Waspada Hadiah dan Undian Palsu',
                'Hadiah besar yang datang tiba-tiba sering dipakai sebagai umpan.',
                "Tanda hadiah palsu:
- Kamu menang padahal tidak pernah ikut undian.
- Diminta klik link atau isi data pribadi untuk klaim.
- Diminta membayar pajak atau biaya administrasi di depan.
- Memakai nama besar (bank, BUMN, marketplace) tanpa kanal resmi.

Langkah aman:
1. Abaikan dan blokir pengirim.
2. Hadiah resmi tidak pernah minta bayar di muka.
3. Cek pengumuman hanya di akun resmi penyelenggara."],

            ['otp_fraud', 'all', 'OTP, PIN, dan CVV Itu Rahasia',
                'Kode ini adalah kunci akunmu. Siapa pun yang memintanya patut dicurigai.',
                "Aturan utama:
- Petugas bank, e-wallet, atau ekspedisi tidak pernah meminta OTP, PIN, atau CVV.
- OTP hanya untuk kamu pakai sendiri saat login atau transaksi.
- Jangan sebutkan kode lewat telepon, chat, atau form mana pun.

Modus yang sering dipakai:
- Mengaku petugas dan minta verifikasi.
- Mengaku kurir dan bilang ada masalah paket.
- Menakut-nakuti dengan transaksi mencurigakan.

Kalau ada yang memaksa minta kode, itu tanda penipuan."],

            ['password_security', 'all', 'Membuat Password yang Kuat',
                'Password lemah membuat akun mudah dibobol.',
                "Hindari:
- Password umum seperti 123456 atau qwerty.
- Tanggal lahir, nama sendiri, atau nomor HP.
- Password yang sama untuk semua akun.

Disarankan:
1. Minimal 12 karakter dengan campuran huruf, angka, dan simbol.
2. Berbeda untuk akun penting seperti email dan m-banking.
3. Aktifkan verifikasi dua langkah bila tersedia.
4. Simpan dengan password manager, bukan di catatan terbuka."],

            ['marketplace_scam', 'all', 'Belanja Online dengan Aman',
                'Sistem pembayaran marketplace melindungi pembeli. Jangan keluar darinya.',
                "Aturan dasar:
- Selalu checkout dan bayar di dalam aplikasi resmi.
- Tolak ajakan transfer langsung ke rekening pribadi.
- Curigai harga yang jauh di bawah pasar.
- Periksa rating, ulasan, dan riwayat toko.

Yang sering jadi jebakan:
- Alasan biar lebih murah kalau bayar di luar aplikasi.
- Admin palsu yang minta login lewat link.
- Penjual yang mendesak segera transfer."],

            ['apk_malware', 'all', 'Bahaya File APK dari Chat',
                'File APK kiriman bisa menyadap SMS, OTP, dan menguras saldo.',
                "Kenapa berbahaya:
- APK bisa membaca SMS termasuk OTP perbankan.
- Bisa mengakses kontak dan menjalankan transaksi diam-diam.

Penyamaran yang umum:
- Undangan, resi, atau foto yang ternyata berakhiran .apk.

Cara aman:
1. Instal aplikasi hanya dari Play Store atau App Store.
2. Jangan buka file .apk dari chat siapa pun.
3. Cek izin aplikasi sebelum menyetujui."],

            ['pinjol_ilegal', 'all', 'Mengenali Pinjol Ilegal',
                'Pinjol ilegal menjebak dengan iming-iming cepat lalu meneror.',
                "Tanda pinjol ilegal:
- Tidak terdaftar di OJK.
- Cair instan tanpa BI checking lewat link atau APK.
- Minta akses kontak, galeri, dan lokasi.
- Bunga sangat tinggi dengan tenor sangat pendek.

Kalau diteror:
1. Jangan panik dan jangan transfer ke rekening pribadi.
2. Cek legalitas layanan di kanal resmi OJK.
3. Laporkan ke Satgas Pasti / OJK."],

            ['job_scam', 'all', 'Lowongan Kerja Palsu',
                'Rekrutmen palsu meminta uang atau data di awal.',
                "Ciri-ciri:
- Diminta biaya pendaftaran, seragam, atau pelatihan di depan.
- Email dari alamat pribadi, bukan domain perusahaan.
- Diterima sangat mudah dan terburu-buru.
- Tugas awal berupa top up atau beli produk dulu.

Langkah aman:
1. Perusahaan resmi tidak memungut biaya rekrutmen.
2. Verifikasi lowongan di situs karier resmi.
3. Jangan kirim data sensitif ke kontak pribadi."],

            ['qris_scam', 'all', 'QRIS dan Bukti Transfer Palsu',
                'Bukti transfer mudah diedit dan QRIS bisa diganti.',
                "Saat menerima pembayaran:
- Jangan percaya foto bukti transfer.
- Pastikan dana benar masuk lewat mutasi rekening di aplikasi resmi.

Saat membayar QRIS:
- Pastikan nama penerima sama dengan nama toko.
- Nama berbeda berarti waspada.

Modus kelebihan transfer:
- Penipu mengaku transfer lebih lalu minta kembalian. Jangan kembalikan sebelum dana benar masuk."],
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
