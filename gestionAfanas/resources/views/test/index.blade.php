<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>{{ $persona->nif }}</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/general.css') }}">
</head>
<body>
	<br><br>
	<h1>{{ $persona->nombre }}</h1>
	<hr>
	{{ $persona->nif }}
	<hr>
	{{ $persona->usuario->name }} | {{ $persona->centro->nombre}}
</body>
</html>
