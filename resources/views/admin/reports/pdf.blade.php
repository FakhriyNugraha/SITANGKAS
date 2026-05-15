<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan SITANGKAS</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1b2a4a; padding: 24px; }
        h1 { color: #1b2a4a; font-size: 22px; margin: 0; }
        .meta { color: #5e7299; font-size: 11px; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th { background: #1b2a4a; color: white; padding: 6px 8px; text-align: left; }
        td { border-bottom: 1px solid #dde3ef; padding: 6px 8px; }
        .footer { margin-top: 25px; font-size: 10px; color: #5e7299; text-align: center; }
        .level { padding: 2px 6px; border-radius: 8px; font-size: 10px; font-weight: bold; }
        .lvl-beginner { background: #fef3e7; color: #b45309; }
        .lvl-intermediate { background: #e0e7f3; color: #1e3a8a; }
        .lvl-advanced { background: #1b2a4a; color: #fff3e0; }
    </style>
</head>
<body>
    <h1>Laporan Hasil Latihan SITANGKAS</h1>
    <div class="meta">
        Dibuat: {{ $generatedAt->format('d M Y H:i') }} &middot;
        Filter:
        @forelse($filters as $k => $v) @if($v) {{ $k }}={{ $v }} | @endif @empty (semua) @endforelse
    </div>

    <table>
        <thead><tr>
            <th>ID</th><th>Tanggal</th><th>User</th><th>Mode</th><th>Kasus</th><th>Skor</th><th>Level</th>
            <th>Phishing</th><th>OTP</th><th>Pwd</th><th>Mkt</th><th>Pinjol</th>
        </tr></thead>
        <tbody>
            @foreach($sessions as $s)
                @php $a = $s->awarenessScore; @endphp
                <tr>
                    <td>#{{ $s->id }}</td>
                    <td>{{ optional($s->started_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $s->user->name ?? '-' }}</td>
                    <td>{{ $s->mode }}</td>
                    <td>{{ $s->completed_cases }}/{{ $s->total_cases }}</td>
                    <td>{{ $s->total_score }}</td>
                    <td>@if($a)<span class="level lvl-{{ $a->awareness_level }}">{{ $a->awareness_level }}</span>@else - @endif</td>
                    <td>{{ $a->phishing_score ?? '-' }}</td>
                    <td>{{ $a->otp_score ?? '-' }}</td>
                    <td>{{ $a->password_score ?? '-' }}</td>
                    <td>{{ $a->marketplace_score ?? '-' }}</td>
                    <td>{{ $a->pinjol_score ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">SITANGKAS &mdash; Sistem Interaktif Tanggap Ancaman Keamanan Siber</div>
</body>
</html>
