<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Books</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
        <!-- Bootstrap Font Icon CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    </head>
    <body>
        <div class="container">
            <h2>@yield('title')</h2>

            <div class="card card-body mb-3">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a href="/" class="nav-link {{ (Request::is('/') ? 'active' : '') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="/books" class="nav-link {{ (Request::is('books') ? 'active' : '') }}">Books</a>
                    </li>
                    <li class="nav-item">
                        <a href="/authors" class="nav-link {{ (Request::is('authors') ? 'active' : '') }}">Authors</a>
                    </li>
                    <li class="nav-item">
                        <a href="/list" class="nav-link {{ (Request::is('list') ? 'active' : '') }}">Export List</a>
                    </li>
                </ul>
            </div>
            
            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
 
            @yield('content')

            <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
            @yield('scripts')
        </div>
    </body>
</html>
