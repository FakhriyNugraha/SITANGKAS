<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CyberCase;
use App\Models\FuzzyIndicator;
use App\Models\KnnTrainingProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminImportController extends Controller
{
    public function index(): View
    {
        return view('admin.imports.index');
    }

    public function importCases(Request $request): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt']]);
        $rows = $this->parseCsv($request->file('file')->getRealPath());

        $required = ['id_kasus', 'kanal', 'kategori', 'nama_kategori', 'teks_skenario',
            'label_risiko', 'level_kesulitan', 'indikator_ideal', 'tindakan_benar', 'feedback_tutor'];
        $errors = $this->validateHeaders($rows[0] ?? [], $required);
        if ($errors) return back()->withErrors(['file' => $errors]);

        $imported = 0; $failed = 0; $failRows = [];
        DB::transaction(function () use ($rows, &$imported, &$failed, &$failRows) {
            foreach ($rows as $i => $row) {
                if ($i === 0) continue;
                try {
                    $indicators = $this->parseIndicators($row['indikator_ideal'] ?? '');
                    $case = CyberCase::updateOrCreate(
                        ['case_code' => trim($row['id_kasus'])],
                        [
                            'channel' => $row['kanal'] ?? 'SMS',
                            'category' => $row['kategori'],
                            'category_name' => $row['nama_kategori'],
                            'scenario_text' => $row['teks_skenario'],
                            'risk_label' => in_array($row['label_risiko'], ['aman', 'mencurigakan', 'berbahaya']) ? $row['label_risiko'] : 'mencurigakan',
                            'difficulty_level' => in_array($row['level_kesulitan'], ['mudah', 'sedang', 'sulit']) ? $row['level_kesulitan'] : 'sedang',
                            'risk_score_rule' => (int) ($row['risk_score_rule'] ?? 0),
                            'ideal_indicators' => $indicators,
                            'correct_action' => $row['tindakan_benar'],
                            'tutor_feedback' => $row['feedback_tutor'],
                            'source_basis' => $row['basis_sumber'] ?? null,
                            'is_active' => true,
                        ]
                    );

                    // Buat opsi default jika belum ada
                    if ($case->options()->count() === 0) {
                        $this->createDefaultOptions($case, $row['tindakan_benar']);
                    }

                    $imported++;
                } catch (\Throwable $e) {
                    $failed++;
                    $failRows[] = "Baris ".($i + 1).": ".$e->getMessage();
                }
            }
        });

        return back()->with('status', "Berhasil import $imported kasus, gagal $failed.")
            ->with('failed_rows', $failRows);
    }

    public function importIndicators(Request $request): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt']]);
        $rows = $this->parseCsv($request->file('file')->getRealPath());

        $required = ['indikator_normal', 'kata_kunci_variasi', 'kategori_relevan', 'bobot_risiko'];
        $errors = $this->validateHeaders($rows[0] ?? [], $required);
        if ($errors) return back()->withErrors(['file' => $errors]);

        $imported = 0; $failed = 0;
        DB::transaction(function () use ($rows, &$imported, &$failed) {
            foreach ($rows as $i => $row) {
                if ($i === 0) continue;
                try {
                    FuzzyIndicator::create([
                        'normal_indicator' => $row['indikator_normal'],
                        'keyword_variation' => $row['kata_kunci_variasi'],
                        'relevant_category' => $row['kategori_relevan'] ?? null,
                        'risk_weight' => (int) ($row['bobot_risiko'] ?? 10),
                        'is_active' => true,
                    ]);
                    $imported++;
                } catch (\Throwable $e) { $failed++; }
            }
        });

        return back()->with('status', "Berhasil import $imported indikator, gagal $failed.");
    }

    public function importKnn(Request $request): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt']]);
        $rows = $this->parseCsv($request->file('file')->getRealPath());

        $required = ['id_profile', 'skor_phishing', 'skor_otp', 'skor_password',
            'skor_marketplace', 'skor_pinjol', 'jumlah_salah', 'rata_waktu_detik',
            'jumlah_bantuan_dibuka', 'level_awareness'];
        $errors = $this->validateHeaders($rows[0] ?? [], $required);
        if ($errors) return back()->withErrors(['file' => $errors]);

        $imported = 0; $failed = 0;
        DB::transaction(function () use ($rows, &$imported, &$failed) {
            foreach ($rows as $i => $row) {
                if ($i === 0) continue;
                try {
                    KnnTrainingProfile::updateOrCreate(
                        ['profile_code' => trim($row['id_profile'])],
                        [
                            'phishing_score' => (int) $row['skor_phishing'],
                            'otp_score' => (int) $row['skor_otp'],
                            'password_score' => (int) $row['skor_password'],
                            'marketplace_score' => (int) $row['skor_marketplace'],
                            'pinjol_score' => (int) $row['skor_pinjol'],
                            'wrong_count' => (int) $row['jumlah_salah'],
                            'avg_time_seconds' => (int) $row['rata_waktu_detik'],
                            'help_opened_count' => (int) $row['jumlah_bantuan_dibuka'],
                            'awareness_level' => strtolower($row['level_awareness']),
                        ]
                    );
                    $imported++;
                } catch (\Throwable $e) { $failed++; }
            }
        });

        return back()->with('status', "Berhasil import $imported profil KNN, gagal $failed.");
    }

    /** Parse CSV jadi array of associative rows; baris pertama header. */
    protected function parseCsv(string $path): array
    {
        $rows = [];
        if (($h = fopen($path, 'r')) !== false) {
            $header = fgetcsv($h);
            if (! $header) return [];
            $header = array_map(fn($v) => trim(strtolower($v)), $header);
            $rows[] = array_combine($header, $header); // baris 0 = header
            while (($r = fgetcsv($h)) !== false) {
                if (count($r) !== count($header)) {
                    $r = array_pad(array_slice($r, 0, count($header)), count($header), null);
                }
                $rows[] = @array_combine($header, $r) ?: [];
            }
            fclose($h);
        }
        return $rows;
    }

    protected function validateHeaders(array $header, array $required): ?string
    {
        $keys = array_keys($header);
        $missing = array_diff($required, $keys);
        if (! empty($missing)) {
            return 'Kolom CSV tidak lengkap: '.implode(', ', $missing);
        }
        return null;
    }

    /** Parse string indikator format "nama:bobot|nama:bobot" atau "nama|nama" */
    protected function parseIndicators(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') return [];
        $parts = preg_split('/\s*[\|;]\s*/', $raw);
        $out = [];
        foreach ($parts as $p) {
            if (str_contains($p, ':')) {
                [$name, $weight] = array_map('trim', explode(':', $p, 2));
                $out[] = ['name' => $name, 'weight' => (int) $weight ?: 10];
            } else {
                $out[] = ['name' => trim($p), 'weight' => 10];
            }
        }
        return array_values(array_filter($out, fn($i) => $i['name'] !== ''));
    }

    protected function createDefaultOptions(CyberCase $case, string $correctAction): void
    {
        $defaults = [
            'Klik link/instruksi yang diberikan.',
            'Balas pesan dan ikuti permintaan.',
            'Abaikan pesan.',
            'Cek melalui aplikasi atau website resmi.',
        ];
        $defaults[] = $correctAction;
        $defaults = array_values(array_unique($defaults));

        foreach ($defaults as $idx => $opt) {
            $case->options()->create([
                'option_text' => $opt,
                'is_correct' => trim($opt) === trim($correctAction),
            ]);
        }
    }
}
