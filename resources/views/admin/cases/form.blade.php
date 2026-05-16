@extends('layouts.admin')

@php
    $editing = $case->exists;
    $indicators = old('indicators', $case->ideal_indicators ?: []);
    $optionRows = old('options');
    if (! $optionRows) {
        $existing = $case->exists ? $case->options()->get(['option_text','is_correct'])->toArray() : [];
        $optionRows = $existing ?: [
            ['option_text' => '', 'is_correct' => true],
            ['option_text' => '', 'is_correct' => false],
            ['option_text' => '', 'is_correct' => false],
            ['option_text' => '', 'is_correct' => false],
        ];
    }
    $correctIdx = old('correct_option');
    if ($correctIdx === null) {
        foreach ($optionRows as $i => $o) {
            if (! empty($o['is_correct'])) { $correctIdx = $i; break; }
        }
        $correctIdx = $correctIdx ?? 0;
    }
@endphp

@section('content')
<div class="mb-5">
    <a href="{{ route('admin.cases.index') }}" class="text-navy-500 text-sm">&lsaquo; Kembali</a>
    <h1 class="text-xl font-bold mt-1">{{ $editing ? 'Edit Kasus' : 'Tambah Kasus Baru' }}</h1>
    <p class="text-sm text-navy-500">Lengkapi skenario, pilihan jawaban, dan tanda bahaya yang seharusnya dikenali user.</p>
</div>

<form method="POST" action="{{ $editing ? route('admin.cases.update', $case) : route('admin.cases.store') }}"
      x-data='@json(["indicators" => array_values($indicators), "options" => array_values($optionRows), "correct" => (int) $correctIdx])'>
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="grid lg:grid-cols-3 gap-4">
        <div class="card lg:col-span-2 space-y-4">
            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium block mb-1">Kode Kasus</label>
                    <input type="text" name="case_code" value="{{ old('case_code', $case->case_code) }}" class="form-input" required placeholder="mis. CYB101">
                </div>
                <div>
                    <label class="text-sm font-medium block mb-1">Kanal</label>
                    <select name="channel" class="form-select" required>
                        @foreach(['SMS','WhatsApp','Email','Telepon','Marketplace Chat','Browser'] as $ch)
                            <option value="{{ $ch }}" @selected(old('channel', $case->channel) === $ch)>{{ $ch }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium block mb-1">Kategori</label>
                    <select name="category" class="form-select" required onchange="document.getElementById('cn').value=this.options[this.selectedIndex].text">
                        @foreach($categoryMap as $k => $v)
                            <option value="{{ $k }}" @selected(old('category', $case->category ?: 'phishing_link') === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium block mb-1">Nama Kategori (tampil)</label>
                    <input id="cn" type="text" name="category_name" value="{{ old('category_name', $case->category_name) }}" class="form-input" required>
                </div>
            </div>

            <div>
                <label class="text-sm font-medium block mb-1">Teks Skenario</label>
                <textarea name="scenario_text" rows="4" class="form-textarea" required placeholder="Tulis isi pesan/skenario seperti yang dilihat user...">{{ old('scenario_text', $case->scenario_text) }}</textarea>
            </div>

            <div class="grid sm:grid-cols-3 gap-3">
                <div>
                    <label class="text-sm font-medium block mb-1">Label Risiko</label>
                    <select name="risk_label" class="form-select">
                        @foreach(['aman'=>'Aman','mencurigakan'=>'Mencurigakan','berbahaya'=>'Berbahaya'] as $k=>$v)
                            <option value="{{ $k }}" @selected(old('risk_label', $case->risk_label) === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium block mb-1">Tingkat Kesulitan</label>
                    <select name="difficulty_level" class="form-select">
                        @foreach(['mudah'=>'Mudah','sedang'=>'Sedang','sulit'=>'Sulit'] as $k=>$v)
                            <option value="{{ $k }}" @selected(old('difficulty_level', $case->difficulty_level ?: 'sedang') === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium block mb-1">Skor Risiko</label>
                    <input type="number" name="risk_score_rule" value="{{ old('risk_score_rule', $case->risk_score_rule ?: 0) }}" class="form-input">
                </div>
            </div>

            <div>
                <label class="text-sm font-medium block mb-1">Penjelasan untuk User (feedback)</label>
                <textarea name="tutor_feedback" rows="3" class="form-textarea" required placeholder="Penjelasan kenapa jawaban benar/salah...">{{ old('tutor_feedback', $case->tutor_feedback) }}</textarea>
            </div>

            <input type="hidden" name="source_basis" value="{{ old('source_basis', $case->source_basis) }}">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $case->is_active ?? true))>
                Kasus aktif (tampil ke user)
            </label>
        </div>

        <div class="space-y-4">
            <div class="card">
                <div class="font-semibold text-sm mb-1">Pilihan Tindakan</div>
                <p class="text-xs text-navy-500 mb-3">Tandai satu pilihan yang <b>benar-benar paling aman</b>. Sisanya harus jelas keliru.</p>
                <template x-for="(opt, i) in options" :key="i">
                    <div class="flex items-start gap-2 mb-2">
                        <input type="radio" name="correct_option" :value="i" x-model.number="correct" class="mt-2.5">
                        <input type="text" :name="`options[${i}][option_text]`" x-model="opt.option_text" class="form-input text-sm" placeholder="Teks pilihan...">
                        <button type="button" @click="options.length>2 && options.splice(i,1)" class="text-rose-500 px-2 py-1" title="Hapus pilihan">&times;</button>
                    </div>
                </template>
                <button type="button" @click="options.push({option_text:'',is_correct:false})" class="btn-secondary text-sm w-full mt-1">+ Tambah Pilihan</button>
            </div>

            <div class="card">
                <div class="font-semibold text-sm mb-1">Tanda Bahaya (indikator)</div>
                <p class="text-xs text-navy-500 mb-3">Kata kunci bahaya yang sebaiknya disebut user dalam alasannya. Kosongkan untuk kasus yang memang aman.</p>
                <template x-for="(ind, i) in indicators" :key="i">
                    <div class="flex items-center gap-2 mb-2">
                        <input type="text" :name="`indicators[${i}][name]`" x-model="ind.name" class="form-input text-sm" placeholder="mis. link tidak resmi">
                        <input type="number" :name="`indicators[${i}][weight]`" x-model="ind.weight" class="form-input text-sm w-20" placeholder="bobot" min="1">
                        <button type="button" @click="indicators.splice(i,1)" class="text-rose-500 px-2 py-1" title="Hapus">&times;</button>
                    </div>
                </template>
                <button type="button" @click="indicators.push({name:'',weight:15})" class="btn-secondary text-sm w-full mt-1">+ Tambah Tanda Bahaya</button>
            </div>

            <button type="submit" class="btn-primary w-full py-2.5">{{ $editing ? 'Simpan Perubahan' : 'Simpan Kasus' }}</button>
        </div>
    </div>
</form>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
