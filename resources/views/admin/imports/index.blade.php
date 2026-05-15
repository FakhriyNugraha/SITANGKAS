@extends('layouts.admin')

@section('content')
<div class="mb-5">
    <h1 class="text-2xl font-bold">Import Dataset CSV</h1>
    <p class="text-navy-500 text-sm">Unggah dataset bawaan SITANGKAS dalam format CSV.</p>
</div>

@if(session('failed_rows') && count(session('failed_rows')))
    <div class="card mb-4 border-rose-300 bg-rose-50">
        <div class="font-semibold text-rose-700 mb-2">Baris yang gagal:</div>
        <ul class="text-xs text-rose-700 space-y-1">
            @foreach(session('failed_rows') as $r)<li>{{ $r }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="grid lg:grid-cols-3 gap-4">
    <form method="POST" action="{{ route('admin.imports.cases') }}" enctype="multipart/form-data" class="card space-y-3">
        @csrf
        <div class="font-semibold">Import Kasus</div>
        <p class="text-xs text-navy-500">File: <code>cyber_cases_training_id.csv</code></p>
        <p class="text-xs text-navy-500">Kolom wajib: id_kasus, kanal, kategori, nama_kategori, teks_skenario, label_risiko, level_kesulitan, indikator_ideal, tindakan_benar, feedback_tutor.</p>
        <p class="text-xs text-navy-500">Format <em>indikator_ideal</em>: <code>nama:bobot|nama:bobot</code> (contoh: <code>link tidak resmi:20|tekanan waktu:15</code>).</p>
        <input type="file" name="file" accept=".csv,.txt" required class="form-input">
        <button class="btn-primary w-full">Upload Kasus</button>
    </form>

    <form method="POST" action="{{ route('admin.imports.indicators') }}" enctype="multipart/form-data" class="card space-y-3">
        @csrf
        <div class="font-semibold">Import Indikator Fuzzy</div>
        <p class="text-xs text-navy-500">File: <code>fuzzy_indicator_dictionary_id.csv</code></p>
        <p class="text-xs text-navy-500">Kolom wajib: indikator_normal, kata_kunci_variasi, kategori_relevan, bobot_risiko.</p>
        <input type="file" name="file" accept=".csv,.txt" required class="form-input">
        <button class="btn-primary w-full">Upload Indikator</button>
    </form>

    <form method="POST" action="{{ route('admin.imports.knn') }}" enctype="multipart/form-data" class="card space-y-3">
        @csrf
        <div class="font-semibold">Import Training KNN</div>
        <p class="text-xs text-navy-500">File: <code>knn_user_awareness_training.csv</code></p>
        <p class="text-xs text-navy-500">Kolom wajib: id_profile, skor_phishing, skor_otp, skor_password, skor_marketplace, skor_pinjol, jumlah_salah, rata_waktu_detik, jumlah_bantuan_dibuka, level_awareness.</p>
        <input type="file" name="file" accept=".csv,.txt" required class="form-input">
        <button class="btn-primary w-full">Upload Training KNN</button>
    </form>
</div>
@endsection
