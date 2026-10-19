<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Verdant UI - Dev')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @verdantAssets
    @stack('head')
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">
@yield('content')

@stack('scripts')
</body>
</html>
