<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" {{ app()->getLocale() === 'ar' ? 'dir="rtl"' : '' }} class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'MMA Championship') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="{{ app()->getLocale() === 'ar' ? 'font-arabic' : '' }}">
    @include('layouts.partials.navbar')

    @yield('content')

    @include('layouts.partials.footer')

    @stack('scripts')
</body>
</html>
