<!DOCTYPE html>
<html lang="en">
    <head>
        <title>WQExplorer</title>
        <script src="{{ asset('packages/jquery-2.2.3.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('styles.css') }}">
        @yield('head')
    </head>
    <body>
        <header>
            <h1>WQExplorer</h1>
            <nav>
                <ul>
                    <li><strong>Programs:</strong></li>
                    @foreach(App\Ocpw::$programs as $key => $name)
                    <li><a href="{{ url('ocpw/'.$key) }}">{{ $name }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </header>
        <main>
            @yield('content')
        </main>
        <footer>
            <p><strong>Copyright &copy; 2016 <a href="http://cloudcompli.com" target="_blank">CloudCompli, Inc.</a></strong><br><em><a href="https://github.com/cloudcompli/wqexplorer" target="_blank">Open Source</a> under the MIT License</em></p>
        </footer>
    </body>
</html>