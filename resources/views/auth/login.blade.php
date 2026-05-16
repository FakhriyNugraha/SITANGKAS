@extends('layouts.app')

@section('body')
<div class="min-h-screen flex items-stretch">
    <div class="hidden md:flex md:w-1/2 navy-gradient text-white p-12 flex-col justify-between">
        <div>
            <div class="flex justify-center mb-6">
                <img src="/images/logo_sistem.png" alt="SITANGKAS" class="w-20 h-20 rounded-xl">
            </div>
            <div class="text-center">
                <div class="font-bold text-2xl leading-none mb-1">SITANGKAS</div>
                <div class="text-[11px] text-navy-200 tracking-wider uppercase">Cyber Awareness</div>
            </div>
        </div>
        <div>
            <h2 class="text-3xl font-bold mb-3">Kenali Modusnya.<br>Jadi Lebih Tanggap Digital.</h2>
            <p class="text-navy-100">Login untuk melanjutkan latihan simulasi keamanan siber.</p>
        </div>
        <div class="text-xs text-navy-300">&copy; {{ date('Y') }} SITANGKAS</div>
    </div>

    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <h1 class="text-2xl font-bold mb-2">Masuk</h1>
            <p class="text-navy-500 mb-6 text-sm">Gunakan akun yang sudah terdaftar.</p>

            @if($errors->any())
                <div class="mb-4 px-4 py-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-medium block mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="form-input">
                </div>
                <div>
                    <label class="text-sm font-medium block mb-1">Password</label>
                    <input type="password" name="password" required class="form-input">
                </div>
                <label class="inline-flex items-center text-sm gap-2">
                    <input type="checkbox" name="remember" class="rounded"> Ingat saya
                </label>
                <button type="submit" class="btn-primary w-full">Masuk</button>
            </form>

            <p class="text-sm text-navy-500 mt-6 text-center">
                Belum punya akun? <a href="{{ route('register') }}" class="text-orange-600 font-semibold hover:underline">Daftar di sini</a>
            </p>
            <div class="mt-6 text-[11px] text-navy-400 bg-navy-50 rounded-lg p-3">
                <div class="font-semibold mb-1">Akun demo:</div>
                <div>Admin: admin@sitangkas.test / admin123</div>
                <div>User: user@sitangkas.test / user123</div>
            </div>
        </div>
    </div>
</div>
@endsection
