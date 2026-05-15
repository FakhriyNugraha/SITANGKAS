@extends('layouts.app')

@section('body')
<div class="min-h-screen flex items-stretch">
    <div class="hidden md:flex md:w-1/2 navy-gradient text-white p-12 flex-col justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 orange-gradient rounded-lg flex items-center justify-center font-bold shield-glow">S</div>
            <div>
                <div class="font-bold text-lg leading-none">SITANGKAS</div>
                <div class="text-[10px] text-navy-200 tracking-wider uppercase">Cyber Awareness</div>
            </div>
        </div>
        <div>
            <h2 class="text-3xl font-bold mb-3">Mulai latihan<br>cyber awareness Anda.</h2>
            <p class="text-navy-100">Daftar gratis dan dapatkan rekomendasi materi sesuai kelemahan Anda.</p>
        </div>
        <div class="text-xs text-navy-300">&copy; {{ date('Y') }} SITANGKAS</div>
    </div>

    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <h1 class="text-2xl font-bold mb-2">Daftar Akun</h1>
            <p class="text-navy-500 mb-6 text-sm">Sudah punya akun? <a href="{{ route('login') }}" class="text-orange-600 font-semibold hover:underline">Masuk di sini</a></p>

            @if($errors->any())
                <div class="mb-4 px-4 py-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-medium block mb-1">Nama lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus class="form-input">
                </div>
                <div>
                    <label class="text-sm font-medium block mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
                </div>
                <div>
                    <label class="text-sm font-medium block mb-1">Password</label>
                    <input type="password" name="password" required minlength="6" class="form-input">
                </div>
                <div>
                    <label class="text-sm font-medium block mb-1">Konfirmasi password</label>
                    <input type="password" name="password_confirmation" required minlength="6" class="form-input">
                </div>
                <button type="submit" class="btn-primary w-full">Daftar Sekarang</button>
            </form>
        </div>
    </div>
</div>
@endsection
