@extends('layouts.admin')

@section('content')
<div class="mb-5">
    <h1 class="text-2xl font-bold">Hasil Latihan User</h1>
    <p class="text-navy-500 text-sm">Filter dan inspeksi sesi simulasi.</p>
</div>

<form class="card mb-4 grid grid-cols-2 lg:grid-cols-6 gap-3 items-end">
    <div class="lg:col-span-2">
        <label class="text-xs uppercase text-navy-500 block mb-1">Cari user</label>
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama atau email..." class="form-input">
    </div>
    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Level</label>
        <select name="level" class="form-select">
            <option value="">Semua</option>
            @foreach(['beginner','intermediate','advanced'] as $l)
                <option value="{{ $l }}" @selected(($filters['level'] ?? '') === $l)>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Status</label>
        <select name="status" class="form-select">
            <option value="">Semua</option>
            <option value="in_progress" @selected(($filters['status'] ?? '') === 'in_progress')>Berjalan</option>
            <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Selesai</option>
        </select>
    </div>
    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Dari</label>
        <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-input">
    </div>
    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Sampai</label>
        <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-input">
    </div>
    <div class="lg:col-span-6 flex gap-2">
        <button class="btn-primary">Filter</button>
        <a href="{{ route('admin.results.index') }}" class="btn-secondary">Reset</a>
    </div>
</form>

<div class="card overflow-x-auto">
    <table class="table-base">
        <thead><tr>
            <th>User</th><th>Mode</th><th>Kasus</th><th>Skor</th><th>Level</th><th>Status</th><th>Tanggal</th><th></th>
        </tr></thead>
        <tbody>
            @forelse($sessions as $s)
                <tr>
                    <td>
                        <div class="font-semibold">{{ $s->user->name ?? '-' }}</div>
                        <div class="text-xs text-navy-500">{{ $s->user->email ?? '-' }}</div>
                    </td>
                    <td class="capitalize">{{ $s->mode }}</td>
                    <td>{{ $s->completed_cases }}/{{ $s->total_cases }}</td>
                    <td class="font-semibold">{{ $s->total_score }}</td>
                    <td>@if($s->awarenessScore)<span class="badge badge-{{ $s->awarenessScore->awareness_level }}">{{ $s->awarenessScore->awareness_level }}</span>@else &mdash; @endif</td>
                    <td>{{ $s->status }}</td>
                    <td class="text-navy-500 text-xs">{{ optional($s->started_at)->format('d M Y H:i') }}</td>
                    <td><a class="text-orange-600 font-semibold" href="{{ route('admin.results.show', $s) }}">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-navy-400 py-6">Tidak ada hasil.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $sessions->links() }}</div>
@endsection
