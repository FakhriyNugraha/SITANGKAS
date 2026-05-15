@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between gap-3 mb-5">
    <div>
        <h1 class="text-2xl font-bold">Data Training KNN</h1>
        <p class="text-navy-500 text-sm">Profil pelatihan untuk klasifikasi level cyber awareness.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.imports.index') }}" class="btn-secondary">Import CSV</a>
        <a href="{{ route('admin.knn.create') }}" class="btn-primary">+ Tambah Profil</a>
    </div>
</div>

<div class="grid grid-cols-3 gap-4 mb-4">
    @foreach(['beginner' => 'orange', 'intermediate' => 'navy', 'advanced' => 'orange'] as $lvl => $color)
        <div class="card">
            <div class="text-xs uppercase tracking-wider text-navy-500 mb-1">{{ $lvl }}</div>
            <div class="text-3xl font-bold count-up">{{ $distribution[$lvl] ?? 0 }}</div>
        </div>
    @endforeach
</div>

<form class="card mb-4 flex gap-3">
    <select name="level" class="form-select max-w-xs">
        <option value="">Semua level</option>
        @foreach(['beginner','intermediate','advanced'] as $l)
            <option value="{{ $l }}" @selected($activeLevel === $l)>{{ $l }}</option>
        @endforeach
    </select>
    <button class="btn-primary">Filter</button>
</form>

<div class="card overflow-x-auto">
    <table class="table-base">
        <thead><tr>
            <th>Profile</th><th>Phishing</th><th>OTP</th><th>Pwd</th><th>Mkt</th><th>Pinjol</th><th>Salah</th><th>Waktu</th><th>Bantuan</th><th>Level</th><th></th>
        </tr></thead>
        <tbody>
            @forelse($profiles as $p)
                <tr>
                    <td class="font-mono text-xs">{{ $p->profile_code }}</td>
                    <td>{{ $p->phishing_score }}</td>
                    <td>{{ $p->otp_score }}</td>
                    <td>{{ $p->password_score }}</td>
                    <td>{{ $p->marketplace_score }}</td>
                    <td>{{ $p->pinjol_score }}</td>
                    <td>{{ $p->wrong_count }}</td>
                    <td>{{ $p->avg_time_seconds }}s</td>
                    <td>{{ $p->help_opened_count }}</td>
                    <td><span class="badge badge-{{ $p->awareness_level }}">{{ $p->awareness_level }}</span></td>
                    <td class="whitespace-nowrap space-x-2">
                        <a href="{{ route('admin.knn.edit', $p) }}" class="text-orange-600 font-semibold">Edit</a>
                        <form method="POST" action="{{ route('admin.knn.destroy', $p) }}" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="text-rose-600 font-semibold">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="11" class="text-center text-navy-400 py-6">Belum ada data training.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $profiles->links() }}</div>
@endsection
