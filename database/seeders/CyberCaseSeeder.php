<?php

namespace Database\Seeders;

use App\Models\CyberCase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CyberCaseSeeder extends Seeder
{
    public function run(): void
    {
        // Opsi tindakan umum (template). Index ke-3 = paling aman.
        $opts = [
            'Klik link / ikuti instruksinya.',
            'Balas dan berikan data yang diminta.',
            'Abaikan dan blokir pengirim.',
            'Cek dulu lewat aplikasi atau kanal resmi.',
        ];

        // [code, channel, category, category_name, scenario, risk, difficulty, indicators[], correct_action, feedback, correct_idx]
        $cases = [
            // ===== LEGITIMATE / Pesan Aman =====
            ['CYB001', 'WhatsApp', 'legitimate', 'Pesan Aman',
                'Halo, ini Andi dari Toko ABC. Pesanan #1234 kakak sudah dikirim via JNE, resi 0098123456. Terima kasih sudah belanja ya.',
                'aman', 'mudah', [],
                'Abaikan dan blokir pengirim.',
                'Pesan ini relatif aman: hanya info pengiriman, tidak meminta OTP/data, tidak ada link mencurigakan, dan tidak memaksa. Kamu tetap bisa verifikasi resi di situs ekspedisi resmi.', 2],
            ['CYB002', 'Email', 'legitimate', 'Pesan Aman',
                'Struk pembayaran listrik PLN bulan ini Rp152.000 sudah diterima. Cek riwayat di aplikasi PLN Mobile. Email ini tidak meminta balasan apa pun.',
                'aman', 'sedang', [],
                'Abaikan dan blokir pengirim.',
                'Pesan resmi biasanya hanya memberi informasi, tidak meminta data rahasia atau klik link mendesak. Verifikasi tetap lewat aplikasi resmi bila ragu.', 2],
            ['CYB003', 'SMS', 'legitimate', 'Pesan Aman',
                'Kode OTP Anda: 882190. JANGAN beri kode ini ke siapa pun, termasuk yang mengaku petugas. Berlaku 5 menit.',
                'aman', 'sulit', [],
                'Abaikan dan blokir pengirim.',
                'Ini SMS OTP resmi untuk dipakai sendiri, bukan penipuan. Yang berbahaya adalah jika ADA orang lain memintamu menyebut kode ini.', 2],

            // ===== PHISHING LINK =====
            ['CYB010', 'SMS', 'phishing_link', 'Link Phishing',
                'Akun Anda akan dinonaktifkan. Aktifkan kembali di sini: http://akun-verif.id/login',
                'berbahaya', 'mudah',
                [['name'=>'link tidak resmi','weight'=>20],['name'=>'minta login','weight'=>15]],
                'Cek dulu lewat aplikasi atau kanal resmi.',
                'Link bukan domain resmi dan langsung meminta login. Akses layanan dengan membuka aplikasi resmi, bukan dari link di pesan.', 3],
            ['CYB011', 'SMS', 'phishing_link', 'Link Phishing',
                'Akun OVO Anda diblokir. Verifikasi sebelum 1x24 jam di http://ov0-cek.id atau saldo hangus.',
                'berbahaya', 'sedang',
                [['name'=>'link tidak resmi','weight'=>20],['name'=>'domain mencurigakan','weight'=>20],['name'=>'tekanan waktu','weight'=>15]],
                'Cek dulu lewat aplikasi atau kanal resmi.',
                'Domain ov0-cek.id meniru ovo.id dan ada tekanan waktu agar kamu panik. Buka aplikasi OVO langsung untuk memastikan.', 3],
            ['CYB012', 'Email', 'phishing_link', 'Link Phishing',
                'Tagihan PLN Rp1.250.000 belum dibayar. Bayar sekarang di http://pln-online.xyz/bayar agar listrik tidak diputus malam ini.',
                'berbahaya', 'sulit',
                [['name'=>'domain mencurigakan','weight'=>20],['name'=>'link tidak resmi','weight'=>15],['name'=>'tekanan waktu','weight'=>15],['name'=>'data pribadi sensitif','weight'=>10]],
                'Cek dulu lewat aplikasi atau kanal resmi.',
                'Domain .xyz bukan milik PLN, nominal dibesar-besarkan, dan ada ancaman pemutusan. Cek tagihan hanya lewat PLN Mobile / pln.co.id.', 3],

            // ===== FAKE GIVEAWAY =====
            ['CYB020', 'SMS', 'fake_giveaway', 'Hadiah Palsu',
                'Selamat! Anda pemenang undian Rp10.000.000. Balas dengan nama dan nomor rekening untuk pencairan.',
                'berbahaya', 'mudah',
                [['name'=>'hadiah palsu','weight'=>20],['name'=>'data pribadi sensitif','weight'=>15]],
                'Abaikan dan blokir pengirim.',
                'Kamu tidak pernah ikut undian apa pun. Hadiah mendadak yang meminta data adalah modus penipuan.', 2],
            ['CYB021', 'WhatsApp', 'fake_giveaway', 'Hadiah Palsu',
                'Anda terpilih dapat hadiah dari Shopee. Klik http://bit.ly/shopee-hadiah dan isi data untuk klaim.',
                'berbahaya', 'sedang',
                [['name'=>'hadiah palsu','weight'=>20],['name'=>'short url mencurigakan','weight'=>15],['name'=>'link tidak resmi','weight'=>15]],
                'Abaikan dan blokir pengirim.',
                'Hadiah resmi tidak lewat short link acak. Link bit.ly menyembunyikan situs palsu pencuri data.', 2],
            ['CYB022', 'SMS', 'fake_giveaway', 'Hadiah Palsu',
                'Selamat menang mobil dari program BUMN! Untuk proses, transfer pajak hadiah Rp500.000 ke rek 1234567 hari ini juga.',
                'berbahaya', 'sulit',
                [['name'=>'hadiah palsu','weight'=>20],['name'=>'rekening pribadi','weight'=>15],['name'=>'tekanan waktu','weight'=>15]],
                'Abaikan dan blokir pengirim.',
                'Hadiah asli tidak pernah meminta bayar pajak di muka ke rekening pribadi, apalagi dengan desakan waktu.', 2],

            // ===== OTP FRAUD =====
            ['CYB030', 'WhatsApp', 'otp_fraud', 'Penipuan OTP/PIN/CVV',
                'Selamat sore, saya admin bank. Demi keamanan, sebutkan kode OTP yang baru masuk ke HP Anda.',
                'berbahaya', 'mudah',
                [['name'=>'meminta OTP','weight'=>25],['name'=>'mengaku petugas','weight'=>15]],
                'Abaikan dan blokir pengirim.',
                'Petugas bank tidak akan pernah meminta OTP. OTP adalah kode rahasia milik kamu sendiri.', 2],
            ['CYB031', 'Telepon', 'otp_fraud', 'Penipuan OTP/PIN/CVV',
                'Halo, ada transaksi mencurigakan di kartu Anda. Untuk membatalkan, sebutkan 3 angka di belakang kartu (CVV) dan PIN ATM.',
                'berbahaya', 'sedang',
                [['name'=>'meminta CVV','weight'=>20],['name'=>'meminta PIN','weight'=>20],['name'=>'mengaku petugas','weight'=>15]],
                'Abaikan dan blokir pengirim.',
                'CVV dan PIN tidak boleh diberikan ke siapa pun. Penipu memanfaatkan rasa takut akan transaksi mencurigakan.', 2],
            ['CYB032', 'WhatsApp', 'otp_fraud', 'Penipuan OTP/PIN/CVV',
                'Pesanan Anda gagal dikirim kurir. Konfirmasi dengan kode verifikasi yang kami kirim agar paket diproses ulang.',
                'berbahaya', 'sulit',
                [['name'=>'meminta OTP','weight'=>25],['name'=>'mengaku petugas','weight'=>15]],
                'Abaikan dan blokir pengirim.',
                'Modus "paket gagal kirim" dipakai untuk mengelabui agar kamu menyerahkan kode verifikasi/OTP. Kurir resmi tidak butuh OTP-mu.', 2],

            // ===== PASSWORD SECURITY =====
            ['CYB040', 'Aplikasi', 'password_security', 'Keamanan Password',
                'Saat daftar akun e-wallet, kamu memilih password: 123456. Apakah ini aman?',
                'mencurigakan', 'mudah',
                [['name'=>'password lemah','weight'=>25]],
                'Cek dulu lewat aplikasi atau kanal resmi.',
                'Password 123456 adalah yang paling sering dicoba peretas. Gunakan kombinasi panjang dan unik.', 3],
            ['CYB041', 'Aplikasi', 'password_security', 'Keamanan Password',
                'Kamu memakai password "nama+tahun lahir" yang sama untuk email, e-wallet, dan media sosial.',
                'mencurigakan', 'sedang',
                [['name'=>'password lemah','weight'=>30]],
                'Cek dulu lewat aplikasi atau kanal resmi.',
                'Password dari data pribadi mudah ditebak, dan memakai satu password untuk semua akun membuat semua jebol jika satu bocor.', 3],
            ['CYB042', 'Email', 'password_security', 'Keamanan Password',
                'Ada email "keamanan akun" meminta kamu kirim balik password lama untuk verifikasi pembaruan sistem.',
                'berbahaya', 'sulit',
                [['name'=>'password lemah','weight'=>15],['name'=>'data pribadi sensitif','weight'=>20]],
                'Abaikan dan blokir pengirim.',
                'Layanan resmi tidak pernah meminta password lama lewat email. Ini upaya mencuri kredensial.', 2],

            // ===== MARKETPLACE SCAM =====
            ['CYB050', 'Marketplace Chat', 'marketplace_scam', 'Scam Marketplace',
                'Kak, biar murah jangan checkout di aplikasi ya. Transfer langsung saja ke rekening saya nanti barang dikirim.',
                'berbahaya', 'mudah',
                [['name'=>'transaksi luar aplikasi','weight'=>25],['name'=>'rekening pribadi','weight'=>15]],
                'Cek dulu lewat aplikasi atau kanal resmi.',
                'Transaksi di luar aplikasi tidak dilindungi. Selalu checkout dan bayar di dalam aplikasi resmi.', 3],
            ['CYB051', 'Marketplace Chat', 'marketplace_scam', 'Scam Marketplace',
                'iPhone baru Rp1.500.000 (harga normal Rp15jt). Stok 1, bayar sekarang lewat transfer atau hangus.',
                'berbahaya', 'sedang',
                [['name'=>'harga tidak wajar','weight'=>20],['name'=>'transaksi luar aplikasi','weight'=>20],['name'=>'tekanan waktu','weight'=>10]],
                'Cek dulu lewat aplikasi atau kanal resmi.',
                'Harga yang terlalu murah + desakan + bayar di luar aplikasi adalah kombinasi khas scam.', 3],
            ['CYB052', 'Marketplace Chat', 'marketplace_scam', 'Scam Marketplace',
                'Saya admin marketplace. Pembayaran kakak tertahan, klik link ini dan login untuk pencairan dana penjual.',
                'berbahaya', 'sulit',
                [['name'=>'minta login','weight'=>20],['name'=>'link tidak resmi','weight'=>20],['name'=>'mengaku petugas','weight'=>15]],
                'Cek dulu lewat aplikasi atau kanal resmi.',
                'Admin marketplace tidak akan meminta login lewat link pribadi. Ini phishing untuk membajak akun penjual.', 3],

            // ===== APK MALWARE =====
            ['CYB060', 'WhatsApp', 'apk_malware', 'APK/Undangan Palsu',
                'Kamu diundang ke pernikahan. Buka file: undangan_pernikahan.apk',
                'berbahaya', 'mudah',
                [['name'=>'file apk mencurigakan','weight'=>30]],
                'Abaikan dan blokir pengirim.',
                'Undangan asli tidak pernah berbentuk APK. APK dari chat bisa menyadap SMS, OTP, dan menguras m-banking.', 2],
            ['CYB061', 'WhatsApp', 'apk_malware', 'APK/Undangan Palsu',
                'Cek resi paket Anda dengan menginstal aplikasi ini: resi_jne_update.apk (di luar Play Store).',
                'berbahaya', 'sedang',
                [['name'=>'file apk mencurigakan','weight'=>25],['name'=>'akses berlebihan','weight'=>15]],
                'Abaikan dan blokir pengirim.',
                'Cek resi cukup lewat web/aplikasi resmi ekspedisi. APK di luar Play Store berisiko malware.', 2],
            ['CYB062', 'WhatsApp', 'apk_malware', 'APK/Undangan Palsu',
                'Foto bukti transfer saya kirim ya kak (file: bukti_transfer.apk), tolong dibuka dan dicek.',
                'berbahaya', 'sulit',
                [['name'=>'file apk mencurigakan','weight'=>25],['name'=>'bukti transfer palsu','weight'=>15]],
                'Abaikan dan blokir pengirim.',
                'Foto seharusnya .jpg, bukan .apk. File "bukti transfer" berformat APK adalah malware penyamar.', 2],

            // ===== PINJOL ILEGAL =====
            ['CYB070', 'SMS', 'pinjol_ilegal', 'Pinjol Ilegal',
                'Butuh dana cepat? Cair 5 menit tanpa jaminan, tanpa BI checking. Daftar: http://dana-kilat.online',
                'berbahaya', 'mudah',
                [['name'=>'pinjol ilegal','weight'=>20],['name'=>'link tidak resmi','weight'=>15]],
                'Abaikan dan blokir pengirim.',
                'Tanpa BI checking dan cair instan lewat link acak adalah ciri pinjol ilegal. Cek legalitas di OJK.', 2],
            ['CYB071', 'WhatsApp', 'pinjol_ilegal', 'Pinjol Ilegal',
                'Install apk pinjaman ini. Syaratnya cukup izinkan akses kontak, galeri, dan lokasi HP Anda.',
                'berbahaya', 'sedang',
                [['name'=>'pinjol ilegal','weight'=>20],['name'=>'akses berlebihan','weight'=>20],['name'=>'file apk mencurigakan','weight'=>10]],
                'Abaikan dan blokir pengirim.',
                'Pinjol ilegal meminta akses kontak & galeri untuk meneror jika telat bayar. Pinjol resmi tidak begitu.', 2],
            ['CYB072', 'WhatsApp', 'pinjol_ilegal', 'Pinjol Ilegal',
                'Tagihan pinjaman Anda Rp3jt jatuh tempo hari ini. Bila tidak bayar, data & kontak akan kami sebar.',
                'berbahaya', 'sulit',
                [['name'=>'pinjol ilegal','weight'=>20],['name'=>'tekanan waktu','weight'=>15],['name'=>'akses berlebihan','weight'=>10]],
                'Abaikan dan blokir pengirim.',
                'Ancaman menyebar data adalah praktik pinjol ilegal. Jangan panik, laporkan ke OJK/Satgas dan jangan bayar ke nomor pribadi.', 2],

            // ===== JOB SCAM =====
            ['CYB080', 'WhatsApp', 'job_scam', 'Lowongan Kerja Palsu',
                'Anda lolos kerja WFH gaji Rp8jt. Transfer biaya pendaftaran Rp250.000 dulu untuk aktivasi akun kerja.',
                'berbahaya', 'mudah',
                [['name'=>'biaya pendaftaran','weight'=>25],['name'=>'rekening pribadi','weight'=>10]],
                'Abaikan dan blokir pengirim.',
                'Perusahaan resmi tidak meminta biaya apa pun di proses rekrutmen.', 2],
            ['CYB081', 'Email', 'job_scam', 'Lowongan Kerja Palsu',
                'Selamat, Anda diterima di PT besar. Kirim foto KTP, NPWP, dan data rekening ke email pribadi HR ini.',
                'berbahaya', 'sedang',
                [['name'=>'data pribadi sensitif','weight'=>20],['name'=>'biaya pendaftaran','weight'=>10]],
                'Abaikan dan blokir pengirim.',
                'Permintaan data sensitif lengkap ke email pribadi (bukan domain perusahaan) adalah modus pencurian identitas.', 2],
            ['CYB082', 'WhatsApp', 'job_scam', 'Lowongan Kerja Palsu',
                'Tugas hari pertama: top up saldo aplikasi Rp1jt, nanti dikembalikan + komisi. Bukti transfer dikirim ke admin.',
                'berbahaya', 'sulit',
                [['name'=>'biaya pendaftaran','weight'=>20],['name'=>'transaksi luar aplikasi','weight'=>15]],
                'Abaikan dan blokir pengirim.',
                'Skema "top up dulu nanti dikembalikan + komisi" adalah penipuan kerja online klasik. Uangmu tidak akan kembali.', 2],

            // ===== QRIS SCAM =====
            ['CYB090', 'SMS', 'qris_scam', 'QRIS/Transfer Palsu',
                'Pembayaran Anda sudah kami transfer (lampiran bukti_tf.jpg dari nomor tak dikenal). Mohon barang segera dikirim.',
                'mencurigakan', 'mudah',
                [['name'=>'bukti transfer palsu','weight'=>25]],
                'Cek dulu lewat aplikasi atau kanal resmi.',
                'Bukti transfer mudah diedit. Pastikan dana benar masuk dengan cek mutasi rekening lewat aplikasi resmi.', 3],
            ['CYB091', 'Marketplace Chat', 'qris_scam', 'QRIS/Transfer Palsu',
                'Scan QRIS ini untuk bayar ya kak (nama penerima berbeda dari toko).',
                'berbahaya', 'sedang',
                [['name'=>'qris palsu','weight'=>25]],
                'Cek dulu lewat aplikasi atau kanal resmi.',
                'Sebelum bayar QRIS, pastikan nama penerima sesuai nama merchant/toko. Nama berbeda = waspada.', 3],
            ['CYB092', 'WhatsApp', 'qris_scam', 'QRIS/Transfer Palsu',
                'Saya sudah transfer lebih Rp500rb, tolong kembalikan selisihnya ke rekening ini ya (bukti terlampir).',
                'berbahaya', 'sulit',
                [['name'=>'bukti transfer palsu','weight'=>20],['name'=>'rekening pribadi','weight'=>15]],
                'Cek dulu lewat aplikasi atau kanal resmi.',
                'Modus "transfer kelebihan minta kembalian" memakai bukti palsu. Jangan kembalikan sebelum dana benar masuk.', 3],
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
                        'correct_action' => $opts[$c[10]],
                        'tutor_feedback' => $c[9],
                        'is_active' => true,
                    ]
                );

                $case->options()->delete();
                foreach ($opts as $idx => $text) {
                    $case->options()->create([
                        'option_text' => $text,
                        'is_correct' => $idx === $c[10],
                    ]);
                }
            }
        });
    }
}
