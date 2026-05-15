@extends('layouts.user')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Pilih Mode Simulasi</h1>
    <p class="text-navy-500 text-sm">Setiap sesi berisi <b>{{ $setting->default_case_count }}</b> kasus.</p>
</div>

<form method="POST" action="{{ route('user.simulations.start') }}" class="space-y-5">
    @csrf

    <div class="grid md:grid-cols-3 gap-4">
        <label class="card card-hover cursor-pointer has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
            <input type="radio" name="mode" value="category" checked class="hidden">
            <div class="font-bold mb-1">Per Kategori</div>
            <p class="text-sm text-navy-500">Latih satu topik spesifik secara mendalam.</p>
        </label>
        <label class="card card-hover cursor-pointer has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50 {{ $setting->is_mixed_mode_enabled ? '' : 'opacity-50 pointer-events-none' }}">
            <input type="radio" name="mode" value="mixed" class="hidden" {{ $setting->is_mixed_mode_enabled ? '' : 'disabled' }}>
            <div class="font-bold mb-1">Campuran</div>
            <p class="text-sm text-navy-500">Kasus diambil acak dari semua kategori aktif.</p>
        </label>
        <label class="card card-hover cursor-pointer has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
            <input type="radio" name="mode" value="recommended" class="hidden">
            <div class="font-bold mb-1">Rekomendasi</div>
            <p class="text-sm text-navy-500">Berdasarkan kategori terlemah Anda.</p>
        </label>
    </div>

    <div class="card">
        <div class="font-semibold mb-3">Pilih Kategori <span class="text-xs text-navy-500">(untuk mode "Per Kategori")</span></div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($categories as $cat)
                <label class="border border-navy-100 rounded-lg p-3 flex items-center gap-3 cursor-pointer has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50 card-hover">
                    <input type="radio" name="category" value="{{ $cat->category }}" {{ $loop->first ? 'checked' : '' }}>
                    <div>
                        <div class="font-semibold text-sm">{{ $cat->category_name }}</div>
                        <div class="text-xs text-navy-500">{{ $cat->total }} kasus tersedia</div>
                    </div>
                </label>
            @empty
                <div class="text-navy-400 text-sm col-span-3">Belum ada kasus aktif. Hubungi admin.</div>
            @endforelse
        </div>
    </div>

    <button class="btn-primary text-base px-6 py-3">Mulai Simulasi &rarr;</button>
</form>
@endsection
