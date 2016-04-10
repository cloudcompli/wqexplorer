<!DOCTYPE html>
<html lang="en">
    <head>
        <title>WQExplorer</title>
        <script src="{{ asset('packages/jquery-2.2.3.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('styles.css') }}">
        @yield('head')
    </head>
    <body>
        <h1>WQExplorer</h1>
        <ul>
            @foreach(App\Ocpw::$programs as $key => $name)
            <li><a href="{{ url('ocpw/'.$key) }}">{{ $name }}</a></li>
            @endforeach
        </ul>
        @yield('content')
    </body>
</html>