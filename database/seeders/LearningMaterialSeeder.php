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
            ['phishing_link', 'all', 'Cara Mengenali Link dan Domain Palsu',
                'Tips cepat membedakan link resmi dan link phishing.',
                "Link phishing dirancang menyerupai situs resmi untuk mencuri data Anda.\n\n**Ciri-ciri link palsu:**\n- Domain mirip tetapi tidak persis (ov0.id vs ovo.id, bnii.com vs bni.co.id).\n- Menggunakan short URL (bit.ly, cutt.ly) untuk menyembunyikan tujuan.\n- Menggunakan TLD aneh seperti .xyz, .top, .icu untuk lembaga resmi.\n- Halaman meminta login + OTP sekaligus.\n\n**Cara aman:**\n1. Jangan klik link dari pesan tidak dikenal.\n2. Akses layanan dengan mengetik alamat resmi secara manual.\n3. Buka aplikasi resmi alih-alih link di pesan.\n4. Periksa domain dengan teliti sebelum login."],

            ['otp_fraud', 'all', 'OTP, PIN, dan CVV Tidak Boleh Dibagikan',
                'Mengapa kode rahasia tidak boleh diberikan kepada siapa pun.',
                "OTP (One Time Password), PIN, dan CVV adalah kunci untuk masuk dan bertransaksi di akun Anda.\n\n**Aturan emas:**\n- Petugas bank, e-wallet, marketplace, atau ekspedisi TIDAK PERNAH meminta OTP/PIN/CVV.\n- OTP hanya digunakan oleh pemilik akun sendiri.\n- Jangan kirim kode melalui chat, telepon, form, atau link.\n\n**Modus penipuan umum:**\n- Mengaku CS bank dan minta verifikasi.\n- Berpura-pura petugas ekspedisi minta konfirmasi paket.\n- Menggunakan akun marketplace palsu minta OTP refund."],

            ['fake_giveaway', 'beginner', 'Mengenali Hadiah dan Giveaway Palsu',
                'Pesan menang hadiah sering dipakai sebagai umpan penipuan.',
                "Tanda hadiah palsu:\n- Datang tiba-tiba dari nomor tidak dikenal.\n- Hadiah sangat besar tanpa Anda pernah ikut undian.\n- Diminta bayar pajak/admin di muka.\n- Diminta isi data pribadi sensitif.\n\nVerifikasi melalui akun resmi penyelenggara. Hadiah resmi tidak pernah minta bayar di depan."],

            ['password_security', 'all', 'Membuat Password Kuat dan Unik',
                'Panduan praktis bikin password yang sulit ditebak.',
                "Hindari password umum: 123456, qwerty, nama, tanggal lahir.\n\n**Tips:**\n- Minimal 12 karakter.\n- Campur huruf besar/kecil, angka, dan simbol.\n- Beda untuk tiap akun (terutama bank dan email).\n- Gunakan password manager.\n- Aktifkan 2FA jika tersedia."],

            ['marketplace_scam', 'all', 'Transaksi Aman di Marketplace',
                'Tetap pakai escrow resmi dari aplikasi.',
                "Aturan dasar:\n- Selalu transaksi di dalam aplikasi resmi (escrow melindungi pembeli).\n- Tolak ajakan transfer langsung ke rekening pribadi.\n- Periksa rating, ulasan, dan riwayat toko.\n- Curigai harga jauh di bawah pasar.\n- Jangan kirim foto KTP/data pribadi ke penjual."],

            ['pinjol_ilegal', 'beginner', 'Ciri Pinjol Ilegal yang Harus Dihindari',
                'Pinjol ilegal sering melakukan kekerasan digital.',
                "Tanda pinjol ilegal:\n- Tidak terdaftar di OJK (cek di website OJK).\n- Minta akses kontak, galeri, mikrofon.\n- Bunga sangat tinggi dan tenor sangat pendek.\n- Disebarkan via link/APK di chat.\n- Mengancam dan menyebarkan data ke kontak peminjam."],

            ['apk_malware', 'all', 'Hati-hati File APK dari Chat',
                'APK undangan/foto/resi sering berisi malware.',
                "APK dari chat berisiko:\n- Mencuri OTP yang masuk via SMS.\n- Membaca daftar kontak dan pesan.\n- Menjalankan aksi tanpa sepengetahuan korban (drain saldo).\n\n**Cara aman:**\n- Instal aplikasi hanya dari Play Store/App Store resmi.\n- Cek izin aplikasi sebelum disetujui.\n- Aktifkan Play Protect."],

            ['job_scam', 'intermediate', 'Mengenali Lowongan Kerja Palsu',
                'Modus rekrutmen palsu sering muncul via WA dan email.',
                "Ciri-ciri:\n- Lowongan datang dari email gratisan (gmail/yahoo), bukan email perusahaan.\n- Diminta biaya seragam/pendaftaran/admin di muka.\n- Wawancara hanya via chat dan terburu-buru.\n- Tugas \"pelatihan\" berupa top-up atau membeli produk dulu.\n\nSelalu cek karier resmi perusahaan via website resmi."],

            ['qris_scam', 'intermediate', 'QRIS dan Bukti Transfer Palsu',
                'Cara aman terima/bayar via QRIS.',
                "Untuk merchant: pastikan QRIS Anda terpasang di tempat aman dan nama merchant sesuai. Untuk pembeli: konfirmasi nama merchant saat scan.\n\n**Bukti transfer palsu** mudah dibuat dengan aplikasi edit gambar. Selalu cek mutasi rekening masuk lewat aplikasi bank/e-wallet resmi, bukan dari foto yang dikirim pembeli."],

            ['legitimate', 'all', 'Membedakan Pesan Aman dan Mencurigakan',
                'Tidak semua pesan dari nomor tidak dikenal adalah penipuan, tetapi tetap waspada.',
                "Pesan relatif aman bila:\n- Tidak meminta OTP/PIN/CVV.\n- Tidak ada link mencurigakan.\n- Tidak ada tekanan waktu berlebihan.\n- Berasal dari kanal/nomor resmi yang bisa diverifikasi.\n\nMeski terlihat aman, jika ragu tetap verifikasi via aplikasi resmi."],
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
