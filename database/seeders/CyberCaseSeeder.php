<?php

namespace Database\Seeders;

use App\Models\CyberCase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CyberCaseSeeder extends Seeder
{
    public function run(): void
    {
        // Setiap kasus: tepat satu pilihan yang ABSOLUT benar, sisanya ABSOLUT salah.
        // [code, channel, category, category_name, scenario, risk, difficulty,
        //  indicators[], options[4], correct_idx, feedback]
        $cases = [
            // ================= LEGITIMATE / AMAN (6, untuk variasi tiap level) =================
            ['CYB001','WhatsApp','legitimate','Pesan Aman',
                'Halo, ini Andi dari Toko ABC. Pesanan #1234 kakak sudah dikirim via JNE, resi 0098123456. Terima kasih sudah belanja.',
                'aman','mudah', [],
                ['Lanjutkan seperti biasa, pesan ini wajar.','Berikan data pribadi yang diminta.','Klik tautan untuk verifikasi.','Segera transfer sesuai instruksi.'],0,
                'Pesan ini wajar: hanya info pengiriman, tidak meminta data rahasia, tidak ada link mencurigakan, dan tidak memaksa. Tidak perlu khawatir, cukup pantau paketnya.'],
            ['CYB002','Email','legitimate','Pesan Aman',
                'Struk pembayaran listrik bulan ini Rp152.000 telah diterima. Cek riwayat di aplikasi resmi PLN Mobile. Email ini tidak meminta balasan.',
                'aman','mudah', [],
                ['Anggap wajar, ini hanya struk pembayaran.','Balas email dengan data rekening.','Klik link yang tidak ada untuk konfirmasi.','Hubungi nomor di email dan beri OTP.'],0,
                'Ini notifikasi resmi yang hanya memberi informasi pembayaran, tidak meminta data atau tindakan apa pun. Aman.'],
            ['CYB003','SMS','legitimate','Pesan Aman',
                'Kode OTP Anda: 882190. JANGAN beri kode ini ke siapa pun, termasuk pihak yang mengaku petugas. Berlaku 5 menit.',
                'aman','sedang', [],
                ['Simpan untuk dipakai sendiri, jangan diberikan ke siapa pun.','Kirim kode ke admin yang menelepon.','Balas SMS dengan kode tersebut.','Bagikan kode ke grup keluarga.'],0,
                'SMS OTP resmi memang untuk dipakai sendiri. Yang berbahaya hanyalah jika ADA orang lain meminta kode ini.'],
            ['CYB004','WhatsApp','legitimate','Pesan Aman',
                'Selamat pagi, reminder dari klinik: jadwal kontrol Anda besok pukul 09.00. Mohon datang tepat waktu. Tidak perlu membalas pesan ini.',
                'aman','mudah', [],
                ['Catat jadwalnya, ini pengingat biasa.','Transfer biaya konsultasi sekarang.','Kirim foto KTP untuk konfirmasi.','Klik tautan pendaftaran ulang.'],0,
                'Pengingat jadwal yang tidak meminta data atau pembayaran. Wajar dan aman.'],
            ['CYB005','Email','legitimate','Pesan Aman',
                'Newsletter bulanan: tips hemat energi di rumah. Anda menerima ini karena berlangganan. Tautan berhenti berlangganan ada di bawah (opsional).',
                'aman','sedang', [],
                ['Baca jika tertarik, ini newsletter biasa.','Masukkan password untuk membaca.','Transfer agar tetap berlangganan.','Kirim data kartu kredit.'],0,
                'Newsletter informatif tanpa permintaan data sensitif. Tidak berbahaya.'],
            ['CYB006','SMS','legitimate','Pesan Aman',
                'Info: saldo akun Anda berhasil bertambah Rp50.000 dari transfer masuk. Cek detail di aplikasi resmi. Pesan otomatis, jangan dibalas.',
                'aman','sedang', [],
                ['Anggap wajar, cek lewat aplikasi resmi bila ingin memastikan.','Balas SMS dengan nomor PIN.','Klik link untuk klaim saldo tambahan.','Hubungi pengirim dan beri OTP.'],0,
                'Notifikasi saldo masuk yang tidak meminta apa pun. Verifikasi cukup lewat aplikasi resmi bila ragu.'],

            // ================= PHISHING LINK =================
            ['CYB010','SMS','phishing_link','Link Phishing',
                'Akun Anda akan dinonaktifkan. Aktifkan kembali di: http://akun-verif.id/login sekarang.',
                'berbahaya','mudah',
                [['name'=>'link tidak resmi','weight'=>20]],
                ['Jangan klik; buka aplikasi/website resmi sendiri.','Klik link dan login di sana.','Balas dan kirim data akun.','Teruskan pesan ke kontak lain.'],0,
                'Tautan bukan domain resmi dan langsung meminta login. Akses layanan dengan membuka aplikasi resminya sendiri, jangan dari link pesan.'],
            ['CYB011','SMS','phishing_link','Link Phishing',
                'Akun OVO Anda diblokir. Verifikasi sebelum 1x24 jam di http://ov0-cek.id atau saldo hangus.',
                'berbahaya','sedang',
                [['name'=>'domain mencurigakan','weight'=>20],['name'=>'tekanan waktu','weight'=>15]],
                ['Jangan klik; cek langsung di aplikasi OVO resmi.','Buka link sebelum 24 jam agar saldo aman.','Masukkan PIN di halaman tersebut.','Balas SMS dengan nomor kartu.'],0,
                'Domain ov0-cek.id meniru ovo.id dan memakai tekanan waktu agar panik. Cek hanya lewat aplikasi resmi.'],
            ['CYB012','Email','phishing_link','Link Phishing',
                'Tagihan Anda tertunggak Rp1.250.000. Bayar sekarang di http://tagihan-online.xyz/bayar agar layanan tidak diputus malam ini.',
                'berbahaya','sulit',
                [['name'=>'domain mencurigakan','weight'=>20],['name'=>'tekanan waktu','weight'=>15]],
                ['Abaikan link; cek tagihan via aplikasi resmi penyedia.','Bayar lewat link agar tidak diputus.','Masukkan data kartu di halaman itu.','Balas email dengan nomor rekening.'],0,
                'Domain .xyz bukan milik penyedia resmi, nominal dibesar-besarkan, dan ada ancaman. Cek tagihan hanya lewat aplikasi resmi.'],

            // ================= FAKE GIVEAWAY =================
            ['CYB020','SMS','fake_giveaway','Hadiah Palsu',
                'Selamat! Anda pemenang undian Rp10.000.000. Balas dengan nama dan nomor rekening untuk pencairan.',
                'berbahaya','mudah',
                [['name'=>'hadiah palsu','weight'=>20]],
                ['Abaikan, ini penipuan berhadiah.','Balas dengan nama dan nomor rekening.','Kirim foto KTP untuk klaim.','Transfer biaya pencairan dulu.'],0,
                'Anda tidak pernah ikut undian. Hadiah mendadak yang meminta data/biaya adalah penipuan.'],
            ['CYB021','WhatsApp','fake_giveaway','Hadiah Palsu',
                'Anda terpilih dapat hadiah dari sebuah toko besar. Klik http://bit.ly/hadiah-klaim dan isi data untuk klaim.',
                'berbahaya','sedang',
                [['name'=>'hadiah palsu','weight'=>15],['name'=>'link tidak resmi','weight'=>15]],
                ['Abaikan dan jangan klik tautannya.','Klik tautan dan isi data diri.','Bagikan ke 10 teman agar dapat hadiah.','Transfer pajak hadiah dulu.'],0,
                'Hadiah resmi tidak lewat short link acak. Link bit.ly menyembunyikan situs pencuri data.'],
            ['CYB022','SMS','fake_giveaway','Hadiah Palsu',
                'Selamat menang mobil dari program resmi! Untuk proses, transfer pajak hadiah Rp500.000 ke rekening 1234567 hari ini juga.',
                'berbahaya','sulit',
                [['name'=>'hadiah palsu','weight'=>15],['name'=>'tekanan waktu','weight'=>15]],
                ['Abaikan, hadiah asli tak minta bayar di muka.','Transfer pajak hadiah agar mobil cair.','Kirim data rekening untuk verifikasi.','Telepon nomor itu dan beri OTP.'],0,
                'Hadiah sah tidak pernah meminta bayar pajak di muka ke rekening pribadi, apalagi dengan desakan waktu.'],

            // ================= OTP FRAUD =================
            ['CYB030','WhatsApp','otp_fraud','Penipuan OTP/PIN/CVV',
                'Selamat sore, saya admin bank. Demi keamanan, sebutkan kode OTP yang baru masuk ke HP Anda.',
                'berbahaya','mudah',
                [['name'=>'meminta OTP','weight'=>25]],
                ['Jangan beri kode apa pun; akhiri percakapan.','Sebutkan kode OTP yang masuk.','Kirim screenshot OTP.','Berikan PIN ATM juga.'],0,
                'Petugas bank tidak pernah meminta OTP. OTP adalah kode rahasia milik Anda sendiri.'],
            ['CYB031','Telepon','otp_fraud','Penipuan OTP/PIN/CVV',
                'Ada transaksi mencurigakan di kartu Anda. Untuk membatalkan, sebutkan 3 angka di belakang kartu dan PIN ATM Anda.',
                'berbahaya','sedang',
                [['name'=>'meminta CVV','weight'=>20],['name'=>'meminta PIN','weight'=>20]],
                ['Tutup telepon; jangan sebutkan data kartu.','Sebutkan CVV dan PIN untuk membatalkan.','Ikuti semua instruksi penelepon.','Beri nomor kartu lengkap.'],0,
                'CVV dan PIN tidak boleh diberikan ke siapa pun. Penipu memakai rasa takut akan transaksi.'],
            ['CYB032','WhatsApp','otp_fraud','Penipuan OTP/PIN/CVV',
                'Paket Anda gagal dikirim. Konfirmasi dengan kode verifikasi yang baru kami kirim agar paket diproses ulang.',
                'berbahaya','sulit',
                [['name'=>'meminta OTP','weight'=>25]],
                ['Jangan berikan kode; cek resi di aplikasi ekspedisi resmi.','Kirim kode verifikasi yang masuk.','Balas dengan data pribadi lengkap.','Transfer ongkos kirim ulang.'],0,
                'Modus "paket gagal kirim" dipakai untuk mencuri OTP. Kurir resmi tidak butuh kode Anda.'],

            // ================= PASSWORD SECURITY =================
            ['CYB040','Aplikasi','password_security','Keamanan Password',
                'Saat membuat akun e-wallet, Anda hendak memakai password: 123456. Apa yang sebaiknya dilakukan?',
                'mencurigakan','mudah',
                [['name'=>'password lemah','weight'=>25]],
                ['Ganti dengan password panjang yang kuat dan unik.','Pakai 123456 saja biar mudah diingat.','Pakai tanggal lahir sebagai password.','Pakai password yang sama dengan email.'],0,
                'Password 123456 paling sering dicoba peretas. Gunakan kombinasi panjang, unik, dan beda tiap akun.'],
            ['CYB041','Aplikasi','password_security','Keamanan Password',
                'Anda memakai satu password yang sama (nama + tahun lahir) untuk email, e-wallet, dan media sosial.',
                'mencurigakan','sedang',
                [['name'=>'password lemah','weight'=>25]],
                ['Buat password berbeda dan kuat untuk tiap akun penting.','Biarkan saja agar tidak lupa.','Tulis di catatan HP tanpa kunci.','Bagikan ke pasangan untuk cadangan.'],0,
                'Password dari data pribadi mudah ditebak; satu password untuk semua akun membuat semuanya jebol bila satu bocor.'],
            ['CYB042','Email','password_security','Keamanan Password',
                'Ada email "tim keamanan" meminta Anda membalas dengan password lama untuk verifikasi pembaruan sistem.',
                'berbahaya','sulit',
                [['name'=>'data pribadi sensitif','weight'=>20]],
                ['Abaikan; layanan resmi tak pernah minta password.','Balas dengan password lama.','Klik link reset di email itu.','Kirim password ke nomor WhatsApp tertera.'],0,
                'Layanan resmi tidak pernah meminta password lewat email. Ini upaya mencuri akun.'],

            // ================= MARKETPLACE SCAM =================
            ['CYB050','Marketplace Chat','marketplace_scam','Scam Marketplace',
                'Kak, biar murah jangan checkout di aplikasi. Transfer langsung ke rekening saya, nanti barang dikirim.',
                'berbahaya','mudah',
                [['name'=>'transaksi luar aplikasi','weight'=>25]],
                ['Tolak; tetap transaksi di dalam aplikasi resmi.','Transfer langsung ke rekening penjual.','Kirim DP dulu lewat e-wallet pribadi.','Beri nomor kartu untuk jaminan.'],0,
                'Transaksi di luar aplikasi tidak terlindungi. Selalu bayar lewat sistem resmi marketplace.'],
            ['CYB051','Marketplace Chat','marketplace_scam','Scam Marketplace',
                'HP baru Rp1.500.000 (harga normal Rp15jt). Stok 1, bayar sekarang via transfer atau hangus.',
                'berbahaya','sedang',
                [['name'=>'harga tidak wajar','weight'=>20],['name'=>'transaksi luar aplikasi','weight'=>15]],
                ['Curigai dan jangan transfer; harga tidak wajar.','Bayar cepat sebelum kehabisan.','Transfer ke rekening pribadi penjual.','Kirim KTP sebagai jaminan.'],0,
                'Harga jauh di bawah pasar + desakan + bayar di luar aplikasi adalah ciri khas penipuan.'],
            ['CYB052','Marketplace Chat','marketplace_scam','Scam Marketplace',
                'Saya admin marketplace. Pembayaran Anda tertahan, klik link ini dan login untuk pencairan dana.',
                'berbahaya','sulit',
                [['name'=>'link tidak resmi','weight'=>20]],
                ['Abaikan; admin resmi tak minta login lewat link.','Klik link dan login akun.','Berikan OTP agar dana cair.','Kirim password ke admin.'],0,
                'Admin resmi tidak meminta login lewat link pribadi. Ini phishing untuk membajak akun.'],

            // ================= APK MALWARE =================
            ['CYB060','WhatsApp','apk_malware','APK/Undangan Palsu',
                'Anda diundang ke pernikahan. Buka file undangan: undangan_pernikahan.apk',
                'berbahaya','mudah',
                [['name'=>'file apk mencurigakan','weight'=>25]],
                ['Jangan unduh; hapus file APK itu.','Unduh dan pasang aplikasinya.','Beri semua izin yang diminta.','Teruskan file ke kontak lain.'],0,
                'Undangan asli tidak berbentuk APK. APK dari chat bisa menyadap SMS, OTP, dan menguras m-banking.'],
            ['CYB061','WhatsApp','apk_malware','APK/Undangan Palsu',
                'Cek resi paket Anda dengan memasang aplikasi ini: resi_update.apk (di luar Play Store).',
                'berbahaya','sedang',
                [['name'=>'file apk mencurigakan','weight'=>25]],
                ['Jangan pasang; cek resi di aplikasi resmi ekspedisi.','Pasang aplikasinya untuk cek resi.','Izinkan akses kontak & SMS.','Bagikan ke grup keluarga.'],0,
                'Cek resi cukup lewat aplikasi/web resmi ekspedisi. APK di luar Play Store berisiko malware.'],
            ['CYB062','WhatsApp','apk_malware','APK/Undangan Palsu',
                'Saya kirim bukti transfer ya kak (file: bukti_transfer.apk), tolong dibuka dan dicek.',
                'berbahaya','sulit',
                [['name'=>'file apk mencurigakan','weight'=>25]],
                ['Jangan buka; bukti transfer tidak berformat APK.','Buka file untuk cek bukti.','Pasang dan beri izin penuh.','Kirim balik ke nomor lain.'],0,
                'Foto/bukti seharusnya .jpg, bukan .apk. File "bukti transfer" berformat APK adalah malware.'],

            // ================= PINJOL ILEGAL =================
            ['CYB070','SMS','pinjol_ilegal','Pinjol Ilegal',
                'Butuh dana cepat? Cair 5 menit tanpa jaminan, tanpa BI checking. Daftar: http://dana-kilat.online',
                'berbahaya','mudah',
                [['name'=>'pinjol ilegal','weight'=>25]],
                ['Tolak; ini ciri pinjaman ilegal.','Daftar lewat link agar cepat cair.','Kirim KTP & swafoto ke nomor itu.','Pasang aplikasinya dan beri izin.'],0,
                'Cair instan tanpa BI checking lewat link acak adalah ciri pinjol ilegal. Cek legalitas di kanal resmi OJK.'],
            ['CYB071','WhatsApp','pinjol_ilegal','Pinjol Ilegal',
                'Pasang aplikasi pinjaman ini. Syaratnya cukup izinkan akses kontak, galeri, dan lokasi HP Anda.',
                'berbahaya','sedang',
                [['name'=>'akses berlebihan','weight'=>20],['name'=>'pinjol ilegal','weight'=>15]],
                ['Tolak; permintaan izin berlebihan itu berbahaya.','Pasang dan izinkan semua akses.','Ambil pinjamannya selagi mudah.','Kirim data keluarga sebagai jaminan.'],0,
                'Pinjol ilegal meminta akses kontak & galeri untuk meneror saat telat bayar. Pinjol resmi tidak begitu.'],
            ['CYB072','WhatsApp','pinjol_ilegal','Pinjol Ilegal',
                'Tagihan pinjaman Anda Rp3jt jatuh tempo hari ini. Jika tidak bayar, data & kontak Anda akan kami sebar.',
                'berbahaya','sulit',
                [['name'=>'pinjol ilegal','weight'=>20],['name'=>'tekanan waktu','weight'=>15]],
                ['Jangan panik; jangan transfer, laporkan ke OJK/Satgas.','Segera transfer ke rekening yang diberi.','Pinjam lagi untuk menutup tagihan.','Beri akses HP agar tidak disebar.'],0,
                'Ancaman menyebar data adalah praktik pinjol ilegal. Jangan bayar ke rekening pribadi; laporkan.'],

            // ================= JOB SCAM =================
            ['CYB080','WhatsApp','job_scam','Lowongan Kerja Palsu',
                'Anda lolos kerja WFH gaji Rp8jt. Transfer biaya pendaftaran Rp250.000 dulu untuk aktivasi akun kerja.',
                'berbahaya','mudah',
                [['name'=>'biaya pendaftaran','weight'=>25]],
                ['Tolak; rekrutmen resmi tidak memungut biaya.','Transfer biaya pendaftaran.','Kirim foto KTP & rekening.','Ikuti tugas top up dulu.'],0,
                'Perusahaan resmi tidak meminta biaya apa pun saat rekrutmen.'],
            ['CYB081','Email','job_scam','Lowongan Kerja Palsu',
                'Selamat, Anda diterima. Kirim foto KTP, NPWP, dan data rekening ke email pribadi HR ini untuk proses.',
                'berbahaya','sedang',
                [['name'=>'data pribadi sensitif','weight'=>20]],
                ['Tolak; verifikasi dulu lewat situs karier resmi.','Kirim semua dokumen ke email itu.','Transfer biaya kontrak kerja.','Beri foto KTP memegang kartu ATM.'],0,
                'Permintaan data sensitif lengkap ke email pribadi (bukan domain perusahaan) adalah pencurian identitas.'],
            ['CYB082','WhatsApp','job_scam','Lowongan Kerja Palsu',
                'Tugas hari pertama: top up saldo aplikasi Rp1jt, nanti dikembalikan plus komisi. Kirim bukti ke admin.',
                'berbahaya','sulit',
                [['name'=>'biaya pendaftaran','weight'=>20]],
                ['Tolak; skema top up berkomisi adalah penipuan.','Top up sesuai instruksi.','Ajak teman ikut agar komisi besar.','Kirim data rekening untuk refund.'],0,
                'Skema "top up dulu nanti dikembalikan plus komisi" adalah penipuan kerja online. Uang tidak akan kembali.'],

            // ================= QRIS / TRANSFER PALSU =================
            ['CYB090','SMS','qris_scam','QRIS/Transfer Palsu',
                'Pembayaran sudah saya transfer (lampiran bukti_tf.jpg dari nomor tak dikenal). Mohon barang segera dikirim.',
                'mencurigakan','mudah',
                [['name'=>'bukti transfer palsu','weight'=>20]],
                ['Cek mutasi rekening resmi dulu sebelum kirim barang.','Langsung kirim barang karena ada bukti.','Balas dan beri nomor resi gratis.','Kembalikan "kelebihan" transfer.'],0,
                'Bukti transfer mudah diedit. Pastikan dana benar masuk lewat mutasi rekening di aplikasi resmi.'],
            ['CYB091','Marketplace Chat','qris_scam','QRIS/Transfer Palsu',
                'Scan QRIS ini untuk membayar ya kak. (Nama penerima pada QRIS berbeda dari nama toko.)',
                'berbahaya','sedang',
                [['name'=>'qris palsu','weight'=>20]],
                ['Batalkan; nama penerima tidak sesuai toko.','Scan dan bayar saja.','Transfer manual ke nomor yang dikirim.','Kirim ulang uang bila diminta.'],0,
                'Sebelum bayar QRIS, pastikan nama penerima sama dengan nama merchant. Nama berbeda berarti penipuan.'],
            ['CYB092','WhatsApp','qris_scam','QRIS/Transfer Palsu',
                'Maaf saya transfer kelebihan Rp500rb, tolong kembalikan selisihnya ke rekening ini (bukti terlampir).',
                'berbahaya','sulit',
                [['name'=>'bukti transfer palsu','weight'=>20]],
                ['Jangan kembalikan sebelum dana benar masuk di mutasi.','Segera kembalikan selisihnya.','Beri OTP agar proses cepat.','Kirim ke rekening lain sesuai arahan.'],0,
                'Modus "transfer kelebihan minta kembalian" memakai bukti palsu. Cek mutasi resmi dulu.'],
        ];

        DB::transaction(function () use ($cases) {
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
                        'correct_action' => $c[8][$c[9]],
                        'tutor_feedback' => $c[10],
                        'is_active' => true,
                    ]
                );

                $case->options()->delete();
                foreach ($c[8] as $idx => $text) {
                    $case->options()->create([
                        'option_text' => $text,
                        'is_correct' => $idx === $c[9],
                    ]);
                }
            }
        });
    }
}
