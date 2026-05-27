<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Skin Quiz — Kominhoo Beauty')</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@yield('head')
</head>
<body class="quiz-wrapper">
@yield('content')
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
</body>
</html>
