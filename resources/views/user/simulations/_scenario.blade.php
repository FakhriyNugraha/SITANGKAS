@php
    $ch = strtolower($case->channel);
    $txt = $case->scenario_text;
@endphp

@if(str_contains($ch, 'sms'))
    {{-- Tampilan SMS --}}
    <div class="chat-phone max-w-sm mx-auto">
        <div class="chat-screen">
            <div class="bg-[#1b2a4a] text-white text-center py-2 text-xs font-semibold">Pesan · Nomor tidak dikenal</div>
            <div class="p-4 space-y-2 min-h-[150px]">
                <div class="bubble-in text-sm">{{ $txt }}</div>
                <div class="text-[10px] text-[#6b7896] pl-1">sekarang</div>
            </div>
        </div>
    </div>
@elseif(str_contains($ch, 'whatsapp'))
    {{-- Tampilan WhatsApp --}}
    <div class="chat-phone max-w-sm mx-auto">
        <div class="chat-screen" style="background:#efeae2">
            <div class="bg-[#075e54] text-white py-2 px-3 text-xs font-semibold flex items-center gap-2">
                <span class="w-7 h-7 rounded-full bg-white/25 flex items-center justify-center"><x-icon name="user" class="w-4 h-4" /></span>
                +62 8xx-xxxx-xxxx
            </div>
            <div class="p-4 space-y-2 min-h-[150px]">
                <div class="bubble-wa text-sm">{{ $txt }}<div class="text-[10px] text-[#667781] text-right mt-1">{{ now()->format('H:i') }}</div></div>
            </div>
        </div>
    </div>
@elseif(str_contains($ch, 'email'))
    {{-- Tampilan Email --}}
    <div class="email-card max-w-lg mx-auto">
        <div class="px-4 py-3 border-b border-[#eef2f7]">
            <div class="text-xs text-[#6b7896]">Dari: <b class="text-[#1b2a4a]">noreply@layanan-info.com</b></div>
            <div class="text-xs text-[#6b7896]">Kepada: kamu</div>
            <div class="font-bold text-sm mt-1">Pemberitahuan Penting</div>
        </div>
        <div class="p-4 text-sm leading-relaxed text-[#1f2937] min-h-[120px]">{{ $txt }}</div>
    </div>
@elseif(str_contains($ch, 'browser') || str_contains($ch, 'website'))
    {{-- Tampilan Browser --}}
    <div class="browser-card max-w-lg mx-auto">
        <div class="browser-bar">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
            <div class="ml-2 flex-1 bg-white border border-[#e2e8f0] rounded-md px-3 py-1 text-xs text-[#6b7896] flex items-center gap-1">
                <x-icon name="lock" class="w-3 h-3" /> situs-tidak-dikenal.web
            </div>
        </div>
        <div class="p-5 text-sm leading-relaxed text-[#1f2937] min-h-[120px]">{{ $txt }}</div>
    </div>
@elseif(str_contains($ch, 'telepon'))
    {{-- Tampilan Telepon --}}
    <div class="chat-phone max-w-xs mx-auto">
        <div class="chat-screen text-center py-8" style="background:#1b2a4a;color:#fff">
            <x-icon name="phone" class="w-12 h-12 mx-auto mb-3 opacity-90" />
            <div class="font-bold">Panggilan masuk…</div>
            <div class="text-xs opacity-70 mb-4">Nomor tidak dikenal</div>
            <div class="bubble-in text-left text-sm mx-4">{{ $txt }}</div>
        </div>
    </div>
@else
    {{-- Chat marketplace / lainnya --}}
    <div class="chat-phone max-w-sm mx-auto">
        <div class="chat-screen">
            <div class="bg-[#e67e22] text-white py-2 px-3 text-xs font-semibold">Chat Penjual</div>
            <div class="p-4 space-y-2 min-h-[150px]">
                <div class="bubble-in text-sm">{{ $txt }}</div>
            </div>
        </div>
    </div>
@endif
