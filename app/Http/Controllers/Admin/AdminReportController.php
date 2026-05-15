<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportExport;
use App\Models\SimulationSession;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $query = SimulationSession::with(['user', 'awarenessScore']);
        $this->applyFilters($query, $request);
        $sessions = $query->latest('id')->limit(500)->get();

        return view('admin.reports.index', [
            'sessions' => $sessions,
            'filters' => $request->only(['level', 'status', 'from', 'to', 'category']),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = SimulationSession::with(['user', 'awarenessScore', 'answers.cyberCase']);
        $this->applyFilters($query, $request);
        $sessions = $query->latest('id')->limit(500)->get();

        ReportExport::create([
            'admin_id' => $request->user()->id,
            'report_type' => 'pdf',
            'filter_payload' => $request->only(['level', 'status', 'from', 'to', 'category']),
        ]);

        $html = view('admin.reports.pdf', [
            'sessions' => $sessions,
            'filters' => $request->only(['level', 'status', 'from', 'to', 'category']),
            'generatedAt' => now(),
        ])->render();

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="laporan-sitangkas-'.now()->format('Ymd-His').'.html"',
        ]);
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $query = SimulationSession::with(['user', 'awarenessScore', 'answers.cyberCase']);
        $this->applyFilters($query, $request);
        $sessions = $query->latest('id')->limit(2000)->get();

        ReportExport::create([
            'admin_id' => $request->user()->id,
            'report_type' => 'excel',
            'filter_payload' => $request->only(['level', 'status', 'from', 'to', 'category']),
        ]);

        $filename = 'laporan-sitangkas-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($sessions) {
            $out = fopen('php://output', 'w');
            // BOM untuk Excel agar utf-8 terdeteksi
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'Session ID', 'Tanggal Mulai', 'User', 'Email', 'Mode', 'Total Kasus',
                'Skor Total', 'Level Awareness',
                'Phishing', 'OTP', 'Password', 'Marketplace', 'Pinjol',
                'Jumlah Salah', 'Rata Waktu (s)', 'Bantuan Dibuka',
            ]);

            foreach ($sessions as $s) {
                $a = $s->awarenessScore;
                fputcsv($out, [
                    $s->id,
                    optional($s->started_at)->format('Y-m-d H:i'),
                    $s->user->name ?? '-',
                    $s->user->email ?? '-',
                    $s->mode,
                    $s->total_cases,
                    $s->total_score,
                    $a->awareness_level ?? '-',
                    $a->phishing_score ?? '-',
                    $a->otp_score ?? '-',
                    $a->password_score ?? '-',
                    $a->marketplace_score ?? '-',
                    $a->pinjol_score ?? '-',
                    $a->wrong_count ?? '-',
                    $a->avg_time_seconds ?? '-',
                    $a->help_opened_count ?? '-',
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    protected function applyFilters($query, Request $request): void
    {
        if ($level = $request->get('level')) {
            $query->whereHas('awarenessScore', fn($q) => $q->where('awareness_level', $level));
        }
        if ($status = $request->get('status')) $query->where('status', $status);
        if ($from = $request->get('from')) $query->whereDate('started_at', '>=', $from);
        if ($to = $request->get('to')) $query->whereDate('started_at', '<=', $to);
        if ($cat = $request->get('category')) $query->where('selected_category', $cat);
    }
}
