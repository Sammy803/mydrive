<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>@yield('title', 'MyDrive')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="app-container">

        @include('components.navbar')

        <div class="app-body">

            @include('components.sidebar')

            <main class="main-content">
                @yield('content')
            </main>

        </div>

    </div>

</body>
</html>