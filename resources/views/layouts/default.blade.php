<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Marvel Viewer</title>
    <link rel="stylesheet" href="{{ url('css/picnic.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
</head>
<body>
@include('partials.header')
<div id="wrapper">

    @yield('content')
</div>
</body>
</html>