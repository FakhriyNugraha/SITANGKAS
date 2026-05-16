@extends('layouts.admin')

@section('content')
<div class="mb-5">
    <h1 class="text-2xl font-bold">Hasil Belajar User</h1>
    <p class="text-navy-500 text-sm">Ringkasan kemampuan tiap pengguna berdasarkan seluruh latihannya.</p>
</div>

<form class="card mb-4 flex gap-2 max-w-md">
    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama atau email user..." class="form-input">
    <button class="btn-primary">Cari</button>
</form>

<div class="card overflow-x-auto">
    <table class="table-base">
        <thead><tr>
            <th>Nama</th><th>Email</th><th class="text-center">Sesi Selesai</th><th class="text-center">Rata-rata Skor</th><th class="text-center">Tingkat Kemampuan</th><th class="text-center">Aksi</th>
        </tr></thead>
        <tbody>
            @forelse($rows as $r)
                <tr>
                    <td class="font-semibold">{{ $r['user']->name }}</td>
                    <td class="text-navy-500">{{ $r['user']->email }}</td>
                    <td class="text-center">{{ $r['sessions'] }}</td>
                    <td class="text-center font-semibold">{{ $r['avg'] }}</td>
                    <td class="text-center">
                        @php $b = ['Pemula'=>'badge-beginner','Menengah'=>'badge-intermediate','Mahir'=>'badge-advanced'][$r['level']] ?? 'badge-mencurigakan'; @endphp
                        <span class="badge {{ $b }}">{{ $r['level'] }}</span>
                    </td>
                    <td class="text-center"><a class="text-orange-600 font-semibold" href="{{ route('admin.results.show', $r['user']) }}">Lihat detail</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-navy-400 py-6">Belum ada user.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
