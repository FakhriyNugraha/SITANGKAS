@extends('layouts.admin')

@section('content')
@php $editing = $profile->exists; @endphp
<div class="mb-5">
    <a href="{{ route('admin.knn.index') }}" class="text-navy-500 text-sm">&lsaquo; Kembali</a>
    <h1 class="text-2xl font-bold mt-1">{{ $editing ? 'Edit' : 'Tambah' }} Profil Training KNN</h1>
</div>

<form method="POST" action="{{ $editing ? route('admin.knn.update', $profile) : route('admin.knn.store') }}" class="card max-w-3xl space-y-4">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Profile Code *</label>
            <input type="text" name="profile_code" value="{{ old('profile_code', $profile->profile_code) }}" class="form-input" required>
        </div>
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Level *</label>
            <select name="awareness_level" class="form-select">
                @foreach(['beginner','intermediate','advanced'] as $l)
                    <option value="{{ $l }}" @selected(old('awareness_level', $profile->awareness_level) === $l)>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-5 gap-3">
        @foreach(['phishing_score', 'otp_score', 'password_score', 'marketplace_score', 'pinjol_score'] as $f)
            <div>
                <label class="text-xs uppercase text-navy-500 block mb-1">{{ ucfirst(str_replace('_', ' ', $f)) }}</label>
                <input type="number" name="{{ $f }}" min="0" max="100" value="{{ old($f, $profile->{$f} ?? 0) }}" class="form-input" required>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-3 gap-3">
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Jumlah Salah</label>
            <input type="number" name="wrong_count" min="0" value="{{ old('wrong_count', $profile->wrong_count ?? 0) }}" class="form-input" required>
        </div>
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Rata-rata Waktu (detik)</label>
            <input type="number" name="avg_time_seconds" min="0" value="{{ old('avg_time_seconds', $profile->avg_time_seconds ?? 0) }}" class="form-input" required>
        </div>
        <div>
            <label class="text-xs uppercase text-navy-500 block mb-1">Bantuan Dibuka</label>
            <input type="number" name="help_opened_count" min="0" value="{{ old('help_opened_count', $profile->help_opened_count ?? 0) }}" class="form-input" required>
        </div>
    </div>

    <button class="btn-primary">{{ $editing ? 'Simpan' : 'Tambah' }}</button>
</form>
@endsection
