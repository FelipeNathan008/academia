<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Academia</title>
</head>

<body>
    <h1>Bem vindo ao laravel 11</h1>
    <p>data atual:{{ \Carbon\Carbon::now()->format('d/m/y H:i:s') }}</p>
</body>

</html>