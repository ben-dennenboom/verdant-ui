<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Verdant UI - Dennenboom')</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Calligraffitti&family=Imperial+Script&family=Karla:ital,wght@0,200..800;1,200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Pacifico&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Karla", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        pre code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
    </style>
    @verdantAssets
    @stack('head')
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
@yield('content')

@stack('scripts')
</body>
</html>
