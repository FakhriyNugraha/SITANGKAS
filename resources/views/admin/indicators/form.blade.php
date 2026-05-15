@extends('layouts.admin')

@section('content')
@php $editing = $indicator->exists; @endphp
<div class="mb-5">
    <a href="{{ route('admin.indicators.index') }}" class="text-navy-500 text-sm">&lsaquo; Kembali</a>
    <h1 class="text-2xl font-bold mt-1">{{ $editing ? 'Edit' : 'Tambah' }} Indikator Fuzzy</h1>
</div>

<form method="POST" action="{{ $editing ? route('admin.indicators.update', $indicator) : route('admin.indicators.store') }}" class="card max-w-2xl space-y-4">
    @csrf
    @if($editing) @method('PUT') @endif

    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Indikator Normal *</label>
        <input type="text" name="normal_indicator" value="{{ old('normal_indicator', $indicator->normal_indicator) }}" class="form-input" required>
    </div>
    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Variasi Kata *</label>
        <input type="text" name="keyword_variation" value="{{ old('keyword_variation', $indicator->keyword_variation) }}" class="form-input" required>
        <p class="text-xs text-navy-500 mt-1">Bentuk kata yang mungkin diketik user (contoh: "linknya aneh", "url palsu").</p>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Kategori Relevan</label>
            <select name="relevant_category" class="form-select">
                <option value="">-- pilih --</option>
                @foreach($categoryMap as $k => $v)
                    <option value="{{ $k }}" @selected(old('relevant_category', $indicator->relevant_category) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Bobot Risiko *</label>
            <input type="number" name="risk_weight" min="1" max="100" value="{{ old('risk_weight', $indicator->risk_weight ?? 10) }}" class="form-input" required>
        </div>
    </div>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $indicator->is_active ?? true))> Aktif</label>

    <button class="btn-primary">{{ $editing ? 'Simpan' : 'Tambah' }}</button>
</form>
@endsection
