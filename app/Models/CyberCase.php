<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CyberCase extends Model
{
    protected $guarded = [];

    protected $casts = [
        'ideal_indicators' => 'array',
        'is_active' => 'boolean',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(CaseOption::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }

    public static function categoryMap(): array
    {
        return [
            'phishing_link' => 'Link Phishing',
            'otp_fraud' => 'Penipuan OTP/PIN/CVV',
            'pinjol_ilegal' => 'Pinjol Ilegal',
            'apk_malware' => 'APK/Undangan Palsu',
            'marketplace_scam' => 'Scam Marketplace',
            'fake_giveaway' => 'Hadiah/Giveaway Palsu',
            'job_scam' => 'Lowongan Kerja Palsu',
            'password_security' => 'Keamanan Password',
            'qris_scam' => 'QRIS/Transfer Palsu',
        ];
    }
}
