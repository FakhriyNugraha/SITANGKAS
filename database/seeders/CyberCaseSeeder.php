<?php

namespace Database\Seeders;

use App\Models\CyberCase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CyberCaseSeeder extends Seeder
{
    public function run(): void
    {
        // 4 default action options template
        $opts = [
            ['Klik link/instruksi yang diberikan.', false],
            ['Balas pesan dan ikuti permintaan.', false],
            ['Abaikan pesan dan blokir pengirim.', false],
            ['Cek melalui aplikasi atau website resmi.', true],
        ];

        $cases = [
            // [code, channel, category, name, scenario, risk, difficulty, indicators, correct_action, feedback, correct_idx]
            ['CYB001', 'SMS', 'phishing_link', 'Link Phishing',
                'Akun OVO Anda diblokir. Verifikasi melalui http://ov0.id/cek sebelum 1x24 jam atau akun terhapus.',
                'berbahaya', 'mudah',
                [['name' => 'link tidak resmi', 'weight' => 20], ['name' => 'domain mencurigakan', 'weight' => 20], ['name' => 'tekanan waktu', 'weight' => 15]],
                'Cek melalui aplikasi atau website resmi.',
                'Pesan ini mencurigakan karena memuat link tidak resmi (ov0.id bukan ovo.id) dan menggunakan tekanan waktu untuk membuat panik.', 3],

            ['CYB002', 'WhatsApp', 'otp_fraud', 'Penipuan OTP/PIN/CVV',
                'Selamat sore. Saya admin BCA. Untuk menjaga keamanan akun, mohon sebutkan kode OTP yang baru saja dikirim ke ponsel Anda.',
                'berbahaya', 'mudah',
                [['name' => 'meminta OTP', 'weight' => 30], ['name' => 'mengaku petugas', 'weight' => 20]],
                'Cek melalui aplikasi atau website resmi.',
                'OTP, PIN, dan CVV adalah rahasia. Petugas resmi BCA tidak akan pernah meminta OTP. Jangan pernah memberikan kode kepada siapa pun.', 3],

            ['CYB003', 'SMS', 'fake_giveaway', 'Hadiah/Giveaway Palsu',
                'Selamat! No HP Anda menang undian Rp50.000.000. Klik http://bit.ly/hadiah-tsel untuk klaim, isi data, dan bayar pajak hadiah Rp250rb.',
                'berbahaya', 'mudah',
                [['name' => 'hadiah palsu', 'weight' => 20], ['name' => 'short url mencurigakan', 'weight' => 15], ['name' => 'link tidak resmi', 'weight' => 15], ['name' => 'tekanan waktu', 'weight' => 10]],
                'Abaikan pesan dan blokir pengirim.',
                'Pesan hadiah palsu sering meminta klik link mencurigakan dan biaya admin. Hadiah resmi tidak pernah meminta biaya di muka.', 2],

            ['CYB004', 'Marketplace Chat', 'marketplace_scam', 'Scam Marketplace',
                'Kak, biar lebih murah jangan beli lewat Shopee ya, transfer langsung ke rekening saya, harga jadi setengahnya. Saya jamin barang sampai.',
                'berbahaya', 'sedang',
                [['name' => 'transaksi luar aplikasi', 'weight' => 25], ['name' => 'harga tidak wajar', 'weight' => 15], ['name' => 'rekening pribadi', 'weight' => 15]],
                'Cek melalui aplikasi atau website resmi.',
                'Transaksi yang dilakukan di luar aplikasi resmi tidak dilindungi sistem escrow. Harga sangat murah dan transfer langsung adalah ciri scam.', 3],

            ['CYB005', 'Aplikasi', 'password_security', 'Keamanan Password',
                'Anda baru saja membuat akun e-wallet. Untuk password Anda memilih: 123456. Apakah ini aman?',
                'mencurigakan', 'mudah',
                [['name' => 'password lemah', 'weight' => 30]],
                'Cek melalui aplikasi atau website resmi.',
                'Password 123456 termasuk yang paling sering dicoba peretas. Gunakan kombinasi panjang, huruf besar/kecil, angka, dan simbol.', 3],

            ['CYB006', 'WhatsApp', 'pinjol_ilegal', 'Pinjol Ilegal',
                'Butuh uang cepat? Pinjam tanpa BI checking, cair 5 menit! Download apk di http://pinjamcepat.online/apk. Cukup KTP + akses kontak.',
                'berbahaya', 'sedang',
                [['name' => 'pinjol ilegal', 'weight' => 25], ['name' => 'akses berlebihan', 'weight' => 20], ['name' => 'file apk mencurigakan', 'weight' => 15], ['name' => 'link tidak resmi', 'weight' => 10]],
                'Abaikan pesan dan blokir pengirim.',
                'Pinjol ilegal sering minta akses kontak, galeri, dan tidak terdaftar OJK. Selalu cek legalitas pinjol di website OJK.', 2],

            ['CYB007', 'WhatsApp', 'apk_malware', 'APK/Undangan Palsu',
                'Anda diundang ke acara pernikahan teman. Buka file: undangan_pernikahan.apk',
                'berbahaya', 'mudah',
                [['name' => 'file apk mencurigakan', 'weight' => 30]],
                'Abaikan pesan dan blokir pengirim.',
                'Undangan asli tidak pernah dalam format APK. APK dari chat berisiko menyadap SMS, kontak, dan menguras saldo m-banking.', 2],

            ['CYB008', 'SMS', 'qris_scam', 'QRIS/Transfer Palsu',
                'Bukti transfer Rp500.000 untuk pembelian Anda. Cek mutasi. (lampiran bukti_tf.jpg dari nomor tidak dikenal)',
                'mencurigakan', 'sedang',
                [['name' => 'bukti transfer palsu', 'weight' => 25]],
                'Cek melalui aplikasi atau website resmi.',
                'Bukti transfer mudah dipalsukan. Selalu konfirmasi penerimaan dengan mengecek mutasi rekening melalui aplikasi resmi bank.', 3],

            ['CYB009', 'Email', 'phishing_link', 'Link Phishing',
                'Re: Penagihan PLN bulan ini. Tagihan Anda Rp1.250.000. Bayar sekarang melalui http://pln-online.xyz/bayar agar listrik tidak diputus.',
                'berbahaya', 'sedang',
                [['name' => 'link tidak resmi', 'weight' => 20], ['name' => 'domain mencurigakan', 'weight' => 20], ['name' => 'tekanan waktu', 'weight' => 15]],
                'Cek melalui aplikasi atau website resmi.',
                'Domain pln-online.xyz bukan domain resmi PLN. Cek tagihan PLN selalu melalui aplikasi PLN Mobile atau website pln.co.id.', 3],

            ['CYB010', 'WhatsApp', 'legitimate', 'Pesan Aman',
                'Selamat malam, ini Andi dari toko ABC. Pesanan Anda nomor #1234 sudah dikirim via JNE resi 1234567890. Terima kasih.',
                'aman', 'mudah',
                [],
                'Abaikan pesan dan blokir pengirim.',
                'Pesan ini relatif aman karena hanya berisi info pengiriman, tidak meminta OTP/data sensitif, dan tidak ada tekanan waktu. Tetap verifikasi resi di situs ekspedisi resmi.', 2],

            ['CYB011', 'Email', 'job_scam', 'Lowongan Kerja Palsu',
                'Selamat! Anda lolos seleksi PT Maju Jaya. Transfer biaya seragam Rp350.000 ke rek BCA 1234567 a.n. HR. Setelah itu langsung bekerja.',
                'berbahaya', 'sedang',
                [['name' => 'biaya pendaftaran', 'weight' => 25], ['name' => 'rekening pribadi', 'weight' => 15]],
                'Abaikan pesan dan blokir pengirim.',
                'Perusahaan resmi tidak meminta biaya apapun di proses rekrutmen. Permintaan transfer ke rekening pribadi adalah modus klasik scam lowongan.', 2],

            ['CYB012', 'SMS', 'otp_fraud', 'Penipuan OTP/PIN/CVV',
                'Mama lagi di RS. Cepat transfer 5jt ke 1234567 BNI. Kirim PIN ATM mama untuk aktifasi via call center.',
                'berbahaya', 'mudah',
                [['name' => 'meminta PIN', 'weight' => 25], ['name' => 'tekanan waktu', 'weight' => 15], ['name' => 'mengaku petugas', 'weight' => 10]],
                'Abaikan pesan dan blokir pengirim.',
                'PIN ATM adalah rahasia mutlak. Permintaan transfer mendadak dengan emosi darurat adalah ciri penipuan klasik.', 2],

            ['CYB013', 'Marketplace Chat', 'marketplace_scam', 'Scam Marketplace',
                'Stok terakhir! Harga normal Rp3jt, untuk kakak hari ini Rp500rb. Tapi bayar dulu transfer BCA, nanti barang dikirim kurir.',
                'berbahaya', 'sedang',
                [['name' => 'harga tidak wajar', 'weight' => 20], ['name' => 'transaksi luar aplikasi', 'weight' => 25], ['name' => 'tekanan waktu', 'weight' => 10]],
                'Cek melalui aplikasi atau website resmi.',
                'Harga turun drastis + transfer di luar aplikasi + mendesak adalah kombinasi scam marketplace. Selalu transaksi di dalam platform resmi.', 3],

            ['CYB014', 'Browser', 'phishing_link', 'Link Phishing',
                'Anda mengakses halaman: https://www.bnii-login.com/internet-banking. Halaman ini meminta nomor rekening, password, dan kode OTP.',
                'berbahaya', 'sulit',
                [['name' => 'domain mencurigakan', 'weight' => 20], ['name' => 'meminta OTP', 'weight' => 20], ['name' => 'data pribadi sensitif', 'weight' => 15]],
                'Cek melalui aplikasi atau website resmi.',
                'Domain bnii-login.com bukan domain BNI resmi (bni.co.id). Ini halaman phishing yang dirancang mencuri kredensial perbankan.', 3],

            ['CYB015', 'WhatsApp', 'password_security', 'Keamanan Password',
                'Saya pakai password "budi1990" untuk semua akun: email, instagram, m-banking. Praktis kan biar ga lupa.',
                'mencurigakan', 'sedang',
                [['name' => 'password lemah', 'weight' => 25]],
                'Cek melalui aplikasi atau website resmi.',
                'Password yang berisi nama dan tahun lahir mudah ditebak. Memakai satu password untuk semua akun sangat berbahaya: jika satu bocor, semuanya jebol.', 3],
        ];

        DB::transaction(function () use ($cases, $opts) {
            foreach ($cases as $c) {
                $case = CyberCase::updateOrCreate(
                    ['case_code' => $c[0]],
                    [
                        'channel' => $c[1],
                        'category' => $c[2],
                        'category_name' => $c[3],
                        'scenario_text' => $c[4],
                        'risk_label' => $c[5],
                        'difficulty_level' => $c[6],
                        'risk_score_rule' => count($c[7]) * 10,
                        'ideal_indicators' => $c[7],
                        'correct_action' => $c[8],
                        'tutor_feedback' => $c[9],
                        'is_active' => true,
                    ]
                );

                $case->options()->delete();
                foreach ($opts as $idx => $o) {
                    $case->options()->create([
                        'option_text' => $o[0],
                        'is_correct' => $idx === $c[10],
                    ]);
                }
            }
        });
    }
}
