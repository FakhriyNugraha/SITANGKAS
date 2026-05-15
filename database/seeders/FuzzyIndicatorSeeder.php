<?php

namespace Database\Seeders;

use App\Models\FuzzyIndicator;
use Illuminate\Database\Seeder;

class FuzzyIndicatorSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // [normal, variation, category, weight]
            ['link tidak resmi', 'link aneh', 'phishing_link', 15],
            ['link tidak resmi', 'tautan palsu', 'phishing_link', 15],
            ['link tidak resmi', 'url palsu', 'phishing_link', 15],
            ['link tidak resmi', 'alamat web mencurigakan', 'phishing_link', 15],
            ['link tidak resmi', 'link mencurigakan', 'phishing_link', 15],
            ['link tidak resmi', 'link palsu', 'phishing_link', 15],
            ['link tidak resmi', 'link tidak dikenal', 'phishing_link', 15],
            ['domain mencurigakan', 'domain aneh', 'phishing_link', 15],
            ['domain mencurigakan', 'domain bukan resmi', 'phishing_link', 15],
            ['domain mencurigakan', 'website palsu', 'phishing_link', 15],
            ['short url mencurigakan', 'tautan dipendekkan', 'phishing_link', 10],
            ['short url mencurigakan', 'url pendek', 'phishing_link', 10],
            ['meminta OTP', 'kode otp', 'otp_fraud', 20],
            ['meminta OTP', 'kode verifikasi', 'otp_fraud', 20],
            ['meminta OTP', 'minta otp', 'otp_fraud', 20],
            ['meminta OTP', 'kasih otp', 'otp_fraud', 20],
            ['meminta OTP', 'kirim kode', 'otp_fraud', 20],
            ['meminta PIN', 'minta pin', 'otp_fraud', 20],
            ['meminta PIN', 'kode pin', 'otp_fraud', 20],
            ['meminta CVV', 'kode cvv', 'otp_fraud', 20],
            ['meminta CVV', 'cvv kartu', 'otp_fraud', 20],
            ['mengaku petugas', 'mengaku bank', 'otp_fraud', 15],
            ['mengaku petugas', 'mengaku cs', 'otp_fraud', 15],
            ['mengaku petugas', 'pura-pura petugas', 'otp_fraud', 15],
            ['hadiah palsu', 'hadiah tidak masuk akal', 'fake_giveaway', 15],
            ['hadiah palsu', 'menang undian', 'fake_giveaway', 15],
            ['hadiah palsu', 'dapat hadiah', 'fake_giveaway', 10],
            ['hadiah palsu', 'iming-iming hadiah', 'fake_giveaway', 15],
            ['tekanan waktu', 'mendesak', 'phishing_link', 15],
            ['tekanan waktu', 'segera', 'phishing_link', 10],
            ['tekanan waktu', 'tenggat waktu', 'phishing_link', 15],
            ['tekanan waktu', 'sekarang juga', 'phishing_link', 10],
            ['tekanan waktu', '24 jam', 'phishing_link', 10],
            ['password lemah', 'password mudah ditebak', 'password_security', 20],
            ['password lemah', 'password gampang', 'password_security', 20],
            ['password lemah', 'pakai tanggal lahir', 'password_security', 15],
            ['password lemah', '123456', 'password_security', 15],
            ['password lemah', 'password berulang', 'password_security', 15],
            ['transaksi luar aplikasi', 'transfer di luar aplikasi', 'marketplace_scam', 20],
            ['transaksi luar aplikasi', 'bayar di luar platform', 'marketplace_scam', 20],
            ['transaksi luar aplikasi', 'transfer langsung', 'marketplace_scam', 15],
            ['transaksi luar aplikasi', 'di luar shopee', 'marketplace_scam', 15],
            ['transaksi luar aplikasi', 'di luar tokopedia', 'marketplace_scam', 15],
            ['harga tidak wajar', 'harga terlalu murah', 'marketplace_scam', 15],
            ['harga tidak wajar', 'diskon mencurigakan', 'marketplace_scam', 15],
            ['pinjol ilegal', 'pinjaman ilegal', 'pinjol_ilegal', 20],
            ['pinjol ilegal', 'tidak terdaftar ojk', 'pinjol_ilegal', 20],
            ['pinjol ilegal', 'pinjaman online ilegal', 'pinjol_ilegal', 20],
            ['pinjol ilegal', 'aplikasi pinjol mencurigakan', 'pinjol_ilegal', 20],
            ['akses berlebihan', 'minta akses kontak', 'pinjol_ilegal', 15],
            ['akses berlebihan', 'akses galeri', 'pinjol_ilegal', 15],
            ['akses berlebihan', 'data pribadi berlebihan', 'pinjol_ilegal', 15],
            ['file apk mencurigakan', 'apk dari chat', 'apk_malware', 20],
            ['file apk mencurigakan', 'undangan apk', 'apk_malware', 20],
            ['file apk mencurigakan', 'file apk palsu', 'apk_malware', 20],
            ['file apk mencurigakan', 'install di luar playstore', 'apk_malware', 20],
            ['biaya pendaftaran', 'minta biaya admin', 'job_scam', 15],
            ['biaya pendaftaran', 'bayar pendaftaran kerja', 'job_scam', 15],
            ['biaya pendaftaran', 'biaya seragam di depan', 'job_scam', 15],
            ['rekening pribadi', 'transfer ke rekening pribadi', 'marketplace_scam', 15],
            ['qris palsu', 'qris tidak sesuai', 'qris_scam', 15],
            ['qris palsu', 'qris bayar palsu', 'qris_scam', 15],
            ['bukti transfer palsu', 'bukti transfer asal', 'qris_scam', 15],
            ['bukti transfer palsu', 'bukti tf edit', 'qris_scam', 15],
            ['data pribadi sensitif', 'minta ktp', 'phishing_link', 15],
            ['data pribadi sensitif', 'minta nomor rekening', 'phishing_link', 15],
        ];

        foreach ($rows as $r) {
            FuzzyIndicator::updateOrCreate(
                [
                    'normal_indicator' => $r[0],
                    'keyword_variation' => $r[1],
                ],
                [
                    'relevant_category' => $r[2],
                    'risk_weight' => $r[3],
                    'is_active' => true,
                ]
            );
        }
    }
}
