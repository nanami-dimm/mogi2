<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>模擬案件2</title>
    <link rel="stylesheet" href="{{asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <h1 class="header__heading">
                <a href="https://localhost/attendance">
                    <img src="{{ asset('/storage/img/logo.svg') }}" alt="COACHTECH" >
                </a>
            </h1> 
            @yield('link')
        </header>
        <div class="content">
            @yield('content')
            
        </div>
    </div>
    <script src="{{ asset('js/script.js') }}" defer></script>

</body>

</html>