@extends('layouts.app')

@section('body')
<div class="min-h-screen">
    <header style="background:linear-gradient(135deg,#1B2A4A 0%,#243B63 55%,#3a2a17 130%)" class="text-white">
        <nav class="max-w-6xl mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="/images/logo_sistem.png" alt="SITANGKAS" class="w-12 h-12 rounded-xl">
                <span class="font-extrabold text-lg tracking-tight">SITANGKAS</span>
            </div>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="btn-primary">Buka Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-white/80 hover:text-white text-sm font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endauth
            </div>
        </nav>

        <div class="max-w-6xl mx-auto px-6 pt-12 pb-20 grid md:grid-cols-2 gap-10 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-5">
                    Kenali Modusnya.<br>
                    Pilih Aksi Amannya.<br>
                    <span class="text-orange-400">Jadi Lebih Tanggap Digital.</span>
                </h1>
                <p class="text-[#c7d2e6] text-lg mb-8 leading-relaxed max-w-lg">
                    Latih dirimu menghadapi penipuan digital sehari-hari, mulai dari SMS hadiah palsu, link phishing, hingga modus pinjol ilegal, lewat simulasi kasus nyata yang interaktif.
                </p>
                @guest
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="btn-primary text-base px-6 py-3">Daftar Sekarang</a>
                        <a href="{{ route('login') }}" class="px-6 py-3 rounded-lg border border-white/25 text-white hover:bg-white/10 transition text-sm font-medium">Masuk</a>
                    </div>
                @endguest
            </div>

            {{-- Ilustrasi keamanan profesional --}}
            <div class="flex justify-center">
                <svg viewBox="0 0 420 360" class="w-full max-w-md" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="ph" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#2563eb"/><stop offset="1" stop-color="#1e40af"/>
                        </linearGradient>
                        <linearGradient id="sh" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" stop-color="#34d399"/><stop offset="1" stop-color="#0d9488"/>
                        </linearGradient>
                    </defs>
                    {{-- floating browser card --}}
                    <g>
                        <rect x="250" y="35" width="140" height="92" rx="10" fill="#3b82f6"/>
                        <rect x="250" y="35" width="140" height="22" rx="10" fill="#60a5fa"/>
                        <circle cx="262" cy="46" r="3" fill="#fff"/><circle cx="273" cy="46" r="3" fill="#fff"/>
                        <rect x="266" y="70" width="46" height="42" rx="6" fill="#f59e0b"/>
                        <rect x="320" y="72" width="58" height="8" rx="4" fill="#bfdbfe"/>
                        <rect x="320" y="88" width="44" height="8" rx="4" fill="#bfdbfe"/>
                    </g>
                    {{-- phone --}}
                    <rect x="120" y="40" width="170" height="300" rx="26" fill="#0f172a"/>
                    <rect x="130" y="58" width="150" height="264" rx="16" fill="url(#ph)"/>
                    <circle cx="205" cy="110" r="26" fill="#fff" opacity="0.95"/>
                    <circle cx="205" cy="101" r="9" fill="#2563eb"/>
                    <path d="M188 128a17 17 0 0 1 34 0z" fill="#2563eb"/>
                    <rect x="150" y="158" width="110" height="22" rx="11" fill="#fff" opacity="0.9"/>
                    <rect x="150" y="190" width="110" height="22" rx="11" fill="#fff" opacity="0.9"/>
                    <rect x="150" y="226" width="110" height="22" rx="11" fill="#f59e0b"/>
                    <rect x="182" y="266" width="46" height="46" rx="9" fill="#fff" opacity="0.95"/>
                    <rect x="190" y="284" width="30" height="22" rx="3" fill="#1e40af"/>
                    <path d="M198 284v-7a7 7 0 0 1 14 0v7" fill="none" stroke="#1e40af" stroke-width="3"/>
                    {{-- big shield check --}}
                    <g transform="translate(20 150)">
                        <path d="M70 0 6 22v50c0 42 30 70 64 84 34-14 64-42 64-84V22z" fill="url(#sh)"/>
                        <path d="M44 78l20 20 36-40" fill="none" stroke="#fff" stroke-width="11" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    {{-- small lock badge --}}
                    <g transform="translate(300 230)">
                        <circle cx="34" cy="34" r="34" fill="#1B2A4A"/>
                        <rect x="20" y="32" width="28" height="22" rx="4" fill="#f59e0b"/>
                        <path d="M26 32v-6a8 8 0 0 1 16 0v6" fill="none" stroke="#f59e0b" stroke-width="4"/>
                    </g>
                </svg>
            </div>
        </div>
    </header>

    <section class="max-w-6xl mx-auto px-6 py-16">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-extrabold text-navy-700">Belajar Keamanan Digital dengan Cara yang Berbeda</h2>
            <p class="text-navy-500 mt-2 max-w-xl mx-auto">Bukan sekadar teori. Kamu menghadapi situasi nyata, mengambil keputusan, dan mendapat penjelasan langsung.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach([
                ['shield-check','Simulasi Kasus Nyata','Hadapi skenario seperti SMS, chat, dan email penipuan yang sering terjadi sehari-hari.'],
                ['cap','Belajar Bertahap','Materi tersusun seperti kurikulum, dari dasar hingga level lanjutan, satu per satu.'],
                ['chart','Pantau Kemampuan','Lihat perkembangan tingkat kewaspadaanmu dan dapatkan rekomendasi materi yang sesuai.'],
            ] as [$ic,$t,$d])
                <div class="card card-hover">
                    <span class="w-11 h-11 rounded-xl orange-gradient text-white flex items-center justify-center mb-3">
                        <x-icon name="{{ $ic }}" class="w-6 h-6" />
                    </span>
                    <h3 class="font-bold text-lg mb-1.5 text-navy-700">{{ $t }}</h3>
                    <p class="text-navy-500 text-sm leading-relaxed">{{ $d }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section style="background:linear-gradient(135deg,#1B2A4A 0%,#243B63 60%,#3a2a17 135%)" class="text-white py-14">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-2xl md:text-3xl font-extrabold mb-3">Siap jadi lebih waspada terhadap penipuan digital?</h2>
            <p class="text-[#c7d2e6] mb-6">Mulai gratis. Belajar bertahap lewat simulasi, dengan umpan balik di setiap langkah.</p>
            @guest
                <a href="{{ route('register') }}" class="btn-primary text-base px-7 py-3">Daftar Sekarang</a>
            @else
                <a href="{{ route('user.dashboard') }}" class="btn-primary text-base px-7 py-3">Lanjut Belajar</a>
            @endguest
        </div>
    </section>

    <footer class="py-6 text-center text-navy-400 text-xs">
        &copy; {{ date('Y') }} SITANGKAS &mdash; Sistem Interaktif Tanggap Ancaman Keamanan Siber
    </footer>
</div>
@endsection
