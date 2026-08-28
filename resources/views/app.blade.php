<!DOCTYPE html>
<html lang="lo">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ $metaTitle ?? $siteName ?? config('app.name', 'ວິທະຍາໄລຄູສົງ ອົງຕື້') }}</title>
    @if(!empty($metaDescription))
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    @if(!empty($metaKeywords))
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif

    @if(!empty($faviconUrl))
        <link rel="icon" href="{{ $faviconUrl }}">
        <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

    <script>
        // The app may be served from a subdirectory (e.g. XAMPP htdocs without a
        // dedicated vhost, where DocumentRoot points above public/). Client-side
        // Inertia navigation must prefix every path with this base or links resolve
        // to the domain root instead of the app.
        window.APP_BASE_PATH = @json(rtrim(parse_url(config('app.url'), PHP_URL_PATH) ?? '', '/'));
    </script>

    {{-- Google Sans is the only display/body typeface; Noto Sans Lao is the Lao-script fallback. --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
    {{-- Google Sans is not served by Google Fonts (proprietary), so it is loaded from the cdnfonts mirror. --}}
    <link href="https://fonts.cdnfonts.com/css/google-sans" rel="stylesheet">

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    @inertiaHead
</head>

<body class="bg-surface font-body-md text-on-surface">
    @inertia
</body>

</html>