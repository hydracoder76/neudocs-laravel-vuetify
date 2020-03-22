<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic|Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oswald:700" rel="stylesheet">
    <script type="text/javascript" src="{{ mix('js/app.js') }}" defer></script>
    <title>{{ $pageTitle }}</title>
</head>
<body>
@csrf
<div id="app">
    @include('parts.header')
    <div class="neu-container neu-default-body">
        @includeWhen(Auth::check() && !Auth::user()->is_temp && Route::current()->getName() !== 'auth.verify','layouts.menu')
        <main id="page-wrap">
            @yield('content')
        </main>

    </div>
    @include('parts.footer')

</div>
</body>
</html>