@extends('layouts.app')

@section('body')
<div class="min-h-screen">
    <header class="navy-gradient text-white">
        <nav class="max-w-6xl mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 orange-gradient rounded-lg flex items-center justify-center font-bold shield-glow">S</div>
                <div>
                    <div class="font-bold text-lg leading-none">SITANGKAS</div>
                    <div class="text-[10px] text-navy-200 tracking-wider uppercase">Cyber Awareness</div>
                </div>
            </div>
            <div class="flex gap-3">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="btn-primary">Buka Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-white/80 hover:text-white px-4 py-2">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar Gratis</a>
                @endauth
            </div>
        </nav>

        <div class="max-w-6xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-orange-200 text-xs uppercase tracking-wider mb-5">Intelligent Tutor System</div>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-5">
                    Kenali Modusnya.<br>
                    Pilih Aksi Amannya.<br>
                    <span class="text-orange-300">Jadi Lebih Tanggap Digital.</span>
                </h1>
                <p class="text-navy-100 text-lg mb-8 leading-relaxed">
                    SITANGKAS melatih Anda menghadapi skenario penipuan digital sehari-hari: SMS hadiah palsu, link phishing, OTP scam, pinjol ilegal, marketplace scam, dan modus lainnya. Belajar lewat simulasi, bukan teori.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn-primary">Mulai Latihan Gratis</a>
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-lg border border-white/30 text-white hover:bg-white/10 transition">Sudah Punya Akun</a>
                </div>
            </div>
            <div class="relative">
                <div class="card text-navy-700 scan-line-bg">
                    <div class="text-[10px] uppercase tracking-wider text-orange-600 font-semibold mb-1">Simulasi · Kanal SMS</div>
                    <div class="text-sm text-navy-500 mb-3">Selamat! Anda mendapat hadiah Rp50.000.000. Klik link berikut untuk klaim:<br><span class="text-rose-600">http://bit.ly/hadiah-tsel</span></div>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center gap-2 p-2 border border-navy-100 rounded-lg"><input type="radio" disabled> Klik link sekarang</label>
                        <label class="flex items-center gap-2 p-2 border border-orange-300 bg-orange-50 rounded-lg"><input type="radio" disabled checked> Abaikan dan blokir</label>
                    </div>
                    <textarea class="form-textarea mt-3 text-sm" placeholder="Tulis alasanmu..." disabled rows="2">link tidak resmi dan mendesak</textarea>
                </div>
            </div>
        </div>
    </header>

    <section class="max-w-6xl mx-auto px-6 py-16 grid md:grid-cols-3 gap-6">
        @foreach([
            ['Simulasi Nyata', 'Kasus diambil dari modus penipuan digital yang sering muncul di SMS, WhatsApp, email, dan marketplace.'],
            ['Fuzzy Matching', 'Alasan jawabanmu dianalisis sistem untuk memastikan kamu paham, bukan sekadar menebak.'],
            ['Level KNN', 'Sistem menentukan level cyber awareness kamu: Beginner, Intermediate, atau Advanced.'],
        ] as [$title, $desc])
            <div class="card card-hover">
                <div class="w-11 h-11 rounded-xl orange-gradient flex items-center justify-center text-white font-bold mb-3">★</div>
                <h3 class="font-bold text-lg mb-2">{{ $title }}</h3>
                <p class="text-navy-500 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
        @endforeach
    </section>

    <section class="navy-gradient text-white py-14">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-3">Siap menghadapi modus penipuan digital?</h2>
            <p class="text-navy-100 mb-6">Daftar gratis dan mulai latihan sekarang. Default 10 kasus per sesi, dengan feedback tutor di setiap jawaban.</p>
            <a href="{{ route('register') }}" class="btn-primary">Daftar Sekarang</a>
        </div>
    </section>

    <footer class="py-6 text-center text-navy-400 text-xs">
        &copy; {{ date('Y') }} SITANGKAS &mdash; Sistem Interaktif Tanggap Ancaman Keamanan Siber
    </footer>
</div>
@endsection
