<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Styles -->
    <link href="{{ asset('admin/css/app.css') }}" rel="stylesheet">
</head>

<body>

<!-- React root DOM -->
<div id="root"></div>

<!-- React JS -->
<script src="{{ asset('admin/js/app.js') }}" defer></script>

</body>
</html>
