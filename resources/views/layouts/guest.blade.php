<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .uzbek-font {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased uzbek-font">
    @yield('content')
</body>
</html>
