@extends('layouts.admin')

@section('content')
@php $editing = $material->exists; @endphp
<div class="mb-5">
    <a href="{{ route('admin.materials.index') }}" class="text-navy-500 text-sm">&lsaquo; Kembali</a>
    <h1 class="text-xl font-bold mt-1">{{ $editing ? 'Edit Materi' : 'Tambah Materi' }}</h1>
    <p class="text-sm text-navy-500">Materi tampil terstruktur otomatis. Gunakan format penulisan di bawah.</p>
</div>

<form method="POST" action="{{ $editing ? route('admin.materials.update', $material) : route('admin.materials.store') }}" class="card max-w-3xl space-y-4">
    @csrf
    @if($editing) @method('PUT') @endif

    <div>
        <label class="text-sm font-medium block mb-1">Judul</label>
        <input type="text" name="title" value="{{ old('title', $material->title) }}" class="form-input" required>
    </div>

    <div class="grid sm:grid-cols-2 gap-3">
        <div>
            <label class="text-sm font-medium block mb-1">Kategori</label>
            <select name="category" class="form-select" required>
                @foreach($categoryMap as $k => $v)
                    <option value="{{ $k }}" @selected(old('category', $material->category) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium block mb-1">Target Pembaca</label>
            <select name="target_level" class="form-select">
                @foreach(['all'=>'Semua','beginner'=>'Pemula','intermediate'=>'Menengah','advanced'=>'Mahir'] as $k=>$v)
                    <option value="{{ $k }}" @selected(old('target_level', $material->target_level ?: 'all') === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="text-sm font-medium block mb-1">Ringkasan Singkat</label>
        <textarea name="summary" rows="2" class="form-textarea" required placeholder="Satu kalimat inti materi...">{{ old('summary', $material->summary) }}</textarea>
    </div>

    <div>
        <label class="text-sm font-medium block mb-1">Isi Materi</label>
        <textarea name="content" rows="14" class="form-textarea font-mono text-sm" required>{{ old('content', $material->content) }}</textarea>
        <div class="text-xs text-navy-500 mt-2 bg-navy-50 rounded-lg p-3 leading-relaxed">
            <b>Panduan format:</b><br>
            <code>## Judul Bab</code> untuk judul bab &middot;
            <code>Sub judul:</code> (diakhiri titik dua) untuk sub-judul<br>
            <code>- teks</code> untuk poin &middot;
            <code>1. teks</code> untuk langkah bernomor<br>
            <code>Contoh: ...</code> untuk kotak contoh &middot;
            <code>Ingat: ...</code> untuk catatan penting
        </div>
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $material->is_active ?? true))>
        Materi aktif (tampil ke user)
    </label>

    <button class="btn-primary">{{ $editing ? 'Simpan Perubahan' : 'Simpan Materi' }}</button>
</form>
@endsection
