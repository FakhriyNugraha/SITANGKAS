@extends('layouts.admin')

@section('content')
@php $editing = $material->exists; @endphp
<div class="mb-5">
    <a href="{{ route('admin.materials.index') }}" class="text-navy-500 text-sm">&lsaquo; Kembali</a>
    <h1 class="text-2xl font-bold mt-1">{{ $editing ? 'Edit' : 'Tambah' }} Materi</h1>
</div>

<form method="POST" action="{{ $editing ? route('admin.materials.update', $material) : route('admin.materials.store') }}" class="card max-w-3xl space-y-4">
    @csrf
    @if($editing) @method('PUT') @endif

    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Judul *</label>
        <input type="text" name="title" value="{{ old('title', $material->title) }}" class="form-input" required>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Kategori *</label>
            <select name="category" class="form-select" required>
                @foreach($categoryMap as $k => $v)
                    <option value="{{ $k }}" @selected(old('category', $material->category) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Target Level *</label>
            <select name="target_level" class="form-select">
                @foreach(['beginner','intermediate','advanced','all'] as $lvl)
                    <option value="{{ $lvl }}" @selected(old('target_level', $material->target_level) === $lvl)>{{ $lvl }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Ringkasan *</label>
        <textarea name="summary" rows="2" class="form-textarea" required>{{ old('summary', $material->summary) }}</textarea>
    </div>
    <div>
        <label class="text-xs uppercase text-navy-500 block mb-1">Konten Materi *</label>
        <textarea name="content" rows="10" class="form-textarea" required>{{ old('content', $material->content) }}</textarea>
        <p class="text-xs text-navy-500 mt-1">Dukung markdown sederhana (**bold**, list dengan -).</p>
    </div>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $material->is_active ?? true))> Aktif</label>
    <button class="btn-primary">{{ $editing ? 'Simpan' : 'Tambah' }}</button>
</form>
@endsection
