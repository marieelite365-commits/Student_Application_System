<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<div class="portal-wrapper">

    {{-- Sidebar --}}
    @include('shared.sidebar')

    <div class="main-area">

        {{-- Navbar --}}
        @include('shared.navbar')

        <div class="content">
            @yield('content')
        </div>

    </div>

</div>

</body>
</html>