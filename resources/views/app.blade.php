<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd">
</head>
<body>
    <div id="app"></div>
    @vite('resources/js/app.js')
</body>
</html>
