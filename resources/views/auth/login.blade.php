<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login - MyDrive</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>

<div class="login-page">

    <div class="login-card">

        <h1>MyDrive</h1>

        <p>
            Sign in with your Google account to manage your Drive files.
        </p>

        @if(session('error'))

            <div class="login-error">
                {{ session('error') }}
            </div>

        @endif

        <a
            href="{{ route('google.redirect') }}"
            class="google-login-button"
        >
            Continue with Google
        </a>

    </div>

</div>

</body>

</html>