@extends('layouts.app')

@section('body')
<div class="min-h-screen flex">

    {{-- ===== Panel kiri: brand showcase ===== --}}
    <div class="hidden md:flex md:w-1/2 lg:w-[55%] navy-gradient text-white relative overflow-hidden">

        <div class="absolute -top-32 -right-32 w-[420px] h-[420px] rounded-full pointer-events-none"
             style="background:radial-gradient(circle, rgba(230,126,34,.16), transparent 70%)"></div>

        <div class="relative z-10 flex flex-col w-full px-14 py-14">

            {{-- Blok brand, di tengah vertikal --}}
            <div class="flex-1 flex flex-col justify-center max-w-md">
                <div class="flex items-center gap-4">
                    <img src="/images/logo_sistem.png" alt="SITANGKAS" class="w-16 h-16 block">
                    <div>
                        <div class="font-extrabold text-2xl leading-none">SITANGKAS</div>
                        <div class="text-[11px] tracking-[0.28em] uppercase text-navy-300 mt-1.5">Cyber Awareness</div>
                    </div>
                </div>

                <h2 class="text-[2.1rem] font-extrabold leading-[1.2] mt-9">
                    Kenali modusnya.<br>
                    <span class="text-brand-orange-soft">Jadi lebih tanggap digital.</span>
                </h2>
                <p class="text-navy-300 mt-4 leading-relaxed">
                    Latih dirimu menghadapi penipuan digital lewat simulasi kasus nyata yang interaktif.
                </p>
            </div>

            {{-- Footer --}}
            <div class="pt-8 border-t border-white/10 text-[13px] text-navy-400">
                &copy; {{ date('Y') }} SITANGKAS &middot; Sistem Interaktif Tanggap Ancaman Keamanan Siber
            </div>
        </div>
    </div>

    {{-- ===== Panel kanan: form ===== --}}
    <div class="flex-1 flex items-center justify-center bg-white px-6 py-12">
        <div class="w-full max-w-sm">

            <div class="md:hidden flex items-center gap-3 mb-10">
                <img src="/images/logo_sistem.png" alt="SITANGKAS" class="w-12 h-12">
                <div>
                    <div class="font-extrabold text-lg leading-none">SITANGKAS</div>
                    <div class="text-[10px] tracking-widest uppercase text-navy-400">Cyber Awareness</div>
                </div>
            </div>

            <h1 class="text-2xl font-bold">Selamat datang kembali</h1>
            <p class="text-navy-500 text-sm mt-1.5">Masuk untuk melanjutkan latihanmu.</p>

            @if($errors->any())
                <div class="mt-5 px-4 py-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-medium block mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" class="form-input">
                </div>
                <div>
                    <label class="text-sm font-medium block mb-1.5">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" class="form-input">
                </div>
                <label class="inline-flex items-center text-sm gap-2 text-navy-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded accent-brand-orange"> Ingat saya
                </label>
                <button type="submit" class="btn-primary w-full py-2.5">Masuk</button>
            </form>

            <p class="text-sm text-navy-500 mt-6 text-center">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-brand-orange font-semibold hover:underline">Daftar di sini</a>
            </p>

            <div class="mt-7 text-[11px] text-navy-500 bg-navy-50 rounded-xl p-3.5">
                <div class="font-semibold text-navy-700 mb-1">Akun demo:</div>
                <div>Admin: admin@sitangkas.test / admin123</div>
                <div>User: user@sitangkas.test / user123</div>
            </div>
        </div>
    </div>
</div>
@endsection
