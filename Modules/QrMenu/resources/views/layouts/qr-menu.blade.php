<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3d1a2e">
    <title>{{ $restaurant->name }} — QR Menu</title>
    <meta name="description" content="{{ $restaurant->description }}">
    <meta property="og:title" content="{{ $restaurant->name }} — Menu">
    <meta property="og:image" content="{{ $restaurant->getFirstMediaUrl('logo') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body class="h-full antialiased" data-theme="special-roma" style="background:#faf5ee;">
    @yield('content'){{ $slot ?? '' }}

    @livewireScripts
    @vite(['resources/js/app.js'])
</body>
</html>
