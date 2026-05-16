@props(['name' => 'shield', 'class' => 'w-6 h-6'])

@php
    $paths = [
        'shield-check' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M12 3 4.5 6v6c0 5 3.5 8.5 7.5 9.75C15.5 20.5 19 17 19 12V6L12 3Z"/>',
        'shield'       => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3 4.5 6v6c0 5 3.5 8.5 7.5 9.75C15.5 20.5 19 17 19 12V6L12 3Z"/>',
        'lock'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75M6.75 10.5h10.5a1.5 1.5 0 0 1 1.5 1.5v6a1.5 1.5 0 0 1-1.5 1.5H6.75a1.5 1.5 0 0 1-1.5-1.5v-6a1.5 1.5 0 0 1 1.5-1.5Z"/>',
        'check-circle' => '<path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 2.25 2.25 4.5-4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
        'x-circle'     => '<path stroke-linecap="round" stroke-linejoin="round" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
        'alert'        => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008M10.34 3.94l-7.4 12.8A1.5 1.5 0 0 0 4.24 19h15.52a1.5 1.5 0 0 0 1.3-2.26l-7.4-12.8a1.5 1.5 0 0 0-2.6 0Z"/>',
        'book'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75A4.5 4.5 0 0 0 8 5H4v12h4a4 4 0 0 1 4 2 4 4 0 0 1 4-2h4V5h-4a4.5 4.5 0 0 0-4 1.75Zm0 0V19"/>',
        'cap'          => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4 2.5 9 12 14l7-3.7V16m-7 4c-3 0-5-1.3-5-3v-3.2M12 20c3 0 5-1.3 5-3v-3.2"/>',
        'play'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.65a.6.6 0 0 1 .92-.5l11 6.35a.6.6 0 0 1 0 1.04l-11 6.35a.6.6 0 0 1-.92-.5V5.65Z"/>',
        'link'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M13.2 10.8a4 4 0 0 0-5.66 0l-3 3a4 4 0 1 0 5.66 5.66l1.5-1.5m-1.9-6.76a4 4 0 0 1 5.66 0l3 3a4 4 0 0 1-5.66 5.66l-1.5-1.5"/>',
        'gift'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13M4 12h16M5 8h14v11a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8Zm7 0S10.5 3 7.75 3a2.25 2.25 0 0 0 0 4.5M12 8s1.5-5 4.25-5a2.25 2.25 0 0 1 0 4.5"/>',
        'key'          => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m-2.5 4.5a6 6 0 1 0-5.6 3.99l1.6 1.6v2.16h2.16l1.34-1.34a6 6 0 0 0 .5-10.41Z"/>',
        'cart'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.5l2.1 12.4a1.5 1.5 0 0 0 1.48 1.25h9.7a1.5 1.5 0 0 0 1.48-1.23L20.25 7H5.1M9 20a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm9 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>',
        'phone'        => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 3h8a1.5 1.5 0 0 1 1.5 1.5v15A1.5 1.5 0 0 1 16 21H8a1.5 1.5 0 0 1-1.5-1.5v-15A1.5 1.5 0 0 1 8 3Zm2.5 15h3"/>',
        'cash'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75h18v10.5H3V6.75Zm9 2.25a3 3 0 1 1 0 6 3 3 0 0 1 0-6ZM6 9.75h.008M18 14.25h.008"/>',
        'briefcase'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 6V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V6m6 4c-3 1.6-6 2.4-9 2.4S6 11.6 3 10M4.5 6h15A1.5 1.5 0 0 1 21 7.5v10.5a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 18V7.5A1.5 1.5 0 0 1 4.5 6Z"/>',
        'qr'           => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h5v5h-5v-5Zm0 11.5h5v5h-5v-5Zm11.5-11.5h5v5h-5v-5ZM15.25 15.25h2v2h-2v-2Zm3 3h2v2h-2v-2Z"/>',
        'doc'          => '<path stroke-linecap="round" stroke-linejoin="round" d="M14 3v4.5a1 1 0 0 0 1 1H19.5M14 3H7a1.5 1.5 0 0 0-1.5 1.5v15A1.5 1.5 0 0 0 7 21h10a1.5 1.5 0 0 0 1.5-1.5V8L14 3Z"/>',
        'star'         => '<path stroke-linejoin="round" d="m12 3.5 2.6 5.27 5.82.85-4.21 4.1.99 5.78L12 16.77 6.8 19.5l.99-5.78-4.21-4.1 5.82-.85L12 3.5Z"/>',
        'arrow-right'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16m0 0-6-6m6 6-6 6"/>',
        'arrow-left'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4m0 0 6-6m-6 6 6 6"/>',
        'user'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 7.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 19.5a7.5 7.5 0 0 1 15 0"/>',
        'chart'        => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7.5 14.5v3M12 9.5v8M16.5 11.5v6"/>',
        'home'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 11.5 12 4l9 7.5M5.5 10v9.5A.5.5 0 0 0 6 20h4v-5h4v5h4a.5.5 0 0 0 .5-.5V10"/>',
        'list'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3.5 6h.01M3.5 12h.01M3.5 18h.01"/>',
        'sparkles'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5 10.2 8 13.5 9 10.2 10 9 13.5 7.8 10 4.5 9 7.8 8 9 4.5Zm8 8 .8 2.2L20 15.5l-2.2.8L17 18.5l-.8-2.2L14 15.5l2.2-.8L17 12.5Z"/>',
        'logout'       => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12H4.5m0 0 3-3m-3 3 3 3M9 6.75V5.25A1.5 1.5 0 0 1 10.5 3.75h7.5A1.5 1.5 0 0 1 19.5 5.25v13.5A1.5 1.5 0 0 1 18 20.25h-7.5A1.5 1.5 0 0 1 9 18.75v-1.5"/>',
        'mail'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.5 6.5h17v11h-17v-11Zm0 .5 8.5 6 8.5-6"/>',
        'chat'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 5.5h15a1.5 1.5 0 0 1 1.5 1.5v8a1.5 1.5 0 0 1-1.5 1.5H9l-4 3.5V16.5H4.5A1.5 1.5 0 0 1 3 15V7a1.5 1.5 0 0 1 1.5-1.5Z"/>',
        'globe'        => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-9c2.5 2.5 2.5 15.5 0 18M12 3C9.5 5.5 9.5 18.5 12 21M3.5 9h17M3.5 15h17"/>',
    ];
    $svg = $paths[$name] ?? $paths['shield'];
@endphp

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" {{ $attributes->merge(['class' => $class]) }} aria-hidden="true">
    {!! $svg !!}
</svg>
