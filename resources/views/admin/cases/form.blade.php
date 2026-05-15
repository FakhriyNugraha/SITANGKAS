@extends('layouts.admin')

@section('content')
@php $editing = $case->exists; @endphp
<div class="mb-5">
    <a href="{{ route('admin.cases.index') }}" class="text-navy-500 text-sm">&lsaquo; Kembali</a>
    <h1 class="text-2xl font-bold mt-1">{{ $editing ? 'Edit' : 'Tambah' }} Kasus Simulasi</h1>
</div>

<form method="POST" action="{{ $editing ? route('admin.cases.update', $case) : route('admin.cases.store') }}" x-data="caseForm()">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="grid lg:grid-cols-3 gap-4">
        <div class="card lg:col-span-2 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs uppercase text-navy-500 block mb-1">Kode Kasus *</label>
                    <input type="text" name="case_code" value="{{ old('case_code', $case->case_code) }}" class="form-input" required>
                </div>
                <div>
                    <label class="text-xs uppercase text-navy-500 block mb-1">Kanal *</label>
                    <input type="text" name="channel" value="{{ old('channel', $case->channel) }}" placeholder="SMS, Email, WhatsApp..." class="form-input" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs uppercase text-navy-500 block mb-1">Kategori (slug) *</label>
                    <select name="category" class="form-select" required>
                        @foreach($categoryMap as $k => $v)
                            <option value="{{ $k }}" @selected(old('category', $case->category) === $k)>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase text-navy-500 block mb-1">Nama Kategori *</label>
                    <input type="text" name="category_name" value="{{ old('category_name', $case->category_name) }}" class="form-input" required>
                </div>
            </div>

            <div>
                <label class="text-xs uppercase text-navy-500 block mb-1">Skenario *</label>
                <textarea name="scenario_text" rows="4" class="form-textarea" required>{{ old('scenario_text', $case->scenario_text) }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="text-xs uppercase text-navy-500 block mb-1">Risiko *</label>
                    <select name="risk_label" class="form-select">
                        @foreach(['aman', 'mencurigakan', 'berbahaya'] as $r)
                            <option value="{{ $r }}" @selected(old('risk_label', $case->risk_label) === $r)>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase text-navy-500 block mb-1">Kesulitan *</label>
                    <select name="difficulty_level" class="form-select">
                        @foreach(['mudah', 'sedang', 'sulit'] as $r)
                            <option value="{{ $r }}" @selected(old('difficulty_level', $case->difficulty_level) === $r)>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase text-navy-500 block mb-1">Risk Score Rule</label>
                    <input type="number" name="risk_score_rule" value="{{ old('risk_score_rule', $case->risk_score_rule) }}" class="form-input">
                </div>
            </div>

            <div>
                <label class="text-xs uppercase text-navy-500 block mb-1">Tindakan Benar (deskripsi) *</label>
                <input type="text" name="correct_action" value="{{ old('correct_action', $case->correct_action) }}" class="form-input" required>
            </div>

            <div>
                <label class="text-xs uppercase text-navy-500 block mb-1">Feedback Tutor *</label>
                <textarea name="tutor_feedback" rows="3" class="form-textarea" required>{{ old('tutor_feedback', $case->tutor_feedback) }}</textarea>
            </div>

            <div>
                <label class="text-xs uppercase text-navy-500 block mb-1">Basis Sumber</label>
                <textarea name="source_basis" rows="2" class="form-textarea">{{ old('source_basis', $case->source_basis) }}</textarea>
            </div>

            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $case->is_active ?? true))> Aktif</label>
        </div>

        <div class="space-y-4">
            <div class="card">
                <div class="font-semibold mb-2">Indikator Ideal</div>
                <p class="text-xs text-navy-500 mb-3">Indikator bahaya yang seharusnya disebutkan user dalam alasan.</p>

                <template x-for="(ind, i) in indicators" :key="i">
                    <div class="flex gap-2 mb-2">
                        <input type="text" :name="`indicators[${i}][name]`" x-model="ind.name" placeholder="nama indikator" class="form-input flex-1">
                        <input type="number" :name="`indicators[${i}][weight]`" x-model="ind.weight" min="1" class="form-input w-20" placeholder="bobot">
                        <button type="button" @click="indicators.splice(i,1)" class="text-rose-500 px-2">×</button>
                    </div>
                </template>
                <button type="button" @click="indicators.push({name:'', weight:10})" class="btn-secondary text-sm w-full">+ Tambah Indikator</button>
            </div>

            <div class="card">
                <div class="font-semibold mb-2">Pilihan Tindakan</div>
                <template x-for="(opt, i) in options" :key="i">
                    <div class="flex items-center gap-2 mb-2">
                        <input type="radio" name="correct_option" :value="i" x-model.number="correctOption">
                        <input type="text" :name="`options[${i}][option_text]`" x-model="opt.option_text" class="form-input flex-1" placeholder="Teks pilihan...">
                        <button type="button" @click="if(options.length>2) options.splice(i,1)" class="text-rose-500 px-2">×</button>
                    </div>
                </template>
                <button type="button" @click="options.push({option_text:'',is_correct:false})" class="btn-secondary text-sm w-full">+ Tambah Pilihan</button>
                <p class="text-xs text-navy-500 mt-2">Pilih radio button untuk menandai jawaban benar.</p>
            </div>

            <button type="submit" class="btn-primary w-full">{{ $editing ? 'Simpan Perubahan' : 'Tambah Kasus' }}</button>
        </div>
    </div>
</form>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function caseForm() {
    return {
        indicators: @json(old('indicators', $case->ideal_indicators ?? [])),
        options: @json(old('options', $case->options()->get(['option_text','is_correct'])->toArray() ?: [
            ['option_text'=>'Klik link/instruksi yang diberikan.','is_correct'=>false],
            ['option_text'=>'Balas pesan dan ikuti permintaan.','is_correct'=>false],
            ['option_text'=>'Abaikan pesan dan blokir pengirim.','is_correct'=>false],
            ['option_text'=>'Cek melalui aplikasi atau website resmi.','is_correct'=>true],
        ])),
        get correctOption() {
            const idx = this.options.findIndex(o => o.is_correct);
            return idx >= 0 ? idx : 0;
        },
        set correctOption(v) {
            this.options = this.options.map((o, i) => ({...o, is_correct: i === v}));
        },
    };
}
</script>
@endsection
