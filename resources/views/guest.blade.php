<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Coalition Auth')</title>
    <link rel="stylesheet" href="{{ elixir("css/app.css") }}">
</head>

<body class="login">
<div>
    @yield('content')
</div>

<script type="text/javascript" src="{{ elixir("js/app.js") }}"></script>
</body>
</html>