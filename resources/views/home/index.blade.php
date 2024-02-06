<!doctype html>
<html lang="{{ preference('lang', 'en') }}" data-bs-theme="{{ preference('theme', 'light') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ (isset($title) ? "$title | " : '') . config('app.name') }}</title>
    <link href="/packages/bootstrap-5.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/packages/bootstrap-icons-1.11.0/bootstrap-icons.css" rel="stylesheet">
    <link href="/css/stylesheet.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex align-items-center">
            <h1 class="m-0">{{ config('app.name') }}</h1>
            <a href="{{ route('dashboard') }}" class="ms-3 btn">{{ Auth::id() ? 'My tasks' : 'Log in' }}</a>
            @auth
            @else
                <a href="{{ route('register') }}" class="btn">Register</a>
            @endauth
            <form action="{{ route('preference.store') }}" method="post">
                @csrf
                <input type="hidden" name="key" value="theme">
                <button type="submit" name="value"
                    value="{{ preference('theme', 'light') == 'light' ? 'dark' : 'light' }}" class="btn"><i
                        class="bi-{{ preference('theme', 'light') == 'light' ? 'moon' : 'sun' }}"></i></button>
            </form>
        </div>
    </div>
    <script src="/packages/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
