<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Mengubah konten materi (teks polos terstruktur) menjadi HTML rapi:
 * - Baris diakhiri ":" menjadi sub-judul
 * - Baris diawali "- " menjadi bullet
 * - Baris diawali "1." dst menjadi list bernomor
 * - Sisanya menjadi paragraf
 * Tanda ** dibersihkan.
 */
class MaterialFormatter
{
    public static function toHtml(string $raw): string
    {
        $raw = str_replace(['**', '__', '`'], '', $raw);
        $lines = preg_split('/\r\n|\r|\n/', trim($raw));

        $html = '';
        $listType = null; // 'ul' | 'ol' | null

        $closeList = function () use (&$html, &$listType) {
            if ($listType) {
                $html .= "</{$listType}>";
                $listType = null;
            }
        };

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                $closeList();
                continue;
            }

            // bullet
            if (Str::startsWith($line, ['- ', '• ', '* '])) {
                if ($listType !== 'ul') { $closeList(); $html .= '<ul class="mat-ul">'; $listType = 'ul'; }
                $html .= '<li>'.e(ltrim($line, '-•* ')).'</li>';
                continue;
            }

            // numbered
            if (preg_match('/^\d+[\.\)]\s+(.*)$/', $line, $m)) {
                if ($listType !== 'ol') { $closeList(); $html .= '<ol class="mat-ol">'; $listType = 'ol'; }
                $html .= '<li>'.e($m[1]).'</li>';
                continue;
            }

            $closeList();

            // callout Contoh / Ingat / Tips
            if (preg_match('/^(Contoh|Ingat|Tips|Catatan)\s*[:—-]\s*(.*)$/u', $line, $m)) {
                $kind = strtolower($m[1]);
                $cls = $kind === 'contoh' ? 'mat-example' : 'mat-note';
                $html .= '<div class="'.$cls.'"><span class="mat-tag">'.e($m[1]).'</span> '.e($m[2]).'</div>';
                continue;
            }

            // judul bab "## Judul"
            if (Str::startsWith($line, '## ')) {
                $html .= '<h3 class="mat-h2">'.e(trim(substr($line, 3))).'</h3>';
                continue;
            }

            // sub-judul (baris pendek diakhiri ":")
            if (Str::endsWith($line, ':') && mb_strlen($line) <= 70) {
                $html .= '<h4 class="mat-h">'.e(rtrim($line, ':')).'</h4>';
                continue;
            }

            $html .= '<p class="mat-p">'.e($line).'</p>';
        }

        $closeList();

        return $html;
    }
}
