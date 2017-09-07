<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">	
	<title>@yield('title', 'Default')</title>
	@yield('css')
	<link rel="stylesheet" href="{{ asset('css/general.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/chosen_1.4.2/chosen.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/trumbowyg/ui/trumbowyg.css') }}">

</head>
<body>
	@include('admin.template.partials.nav')

	<section class="section-admin">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">@yield('title')</h3>
			</div>
			<div class="panel-body">
				@include('flash::message')
				@include('admin.template.partials.errors')
				@yield('content')
			</div>
		</div>
	</section>

	<footer class="admin-footer">
  		<nav class="navbar navbar-default">
  			<div class="collapse navbar-collapse">
  				<p class="navbar-text">Todos los derechos reservados &copy {{ date('Y') }} </p>
  				<p class="navbar-text navbar-right"><b>José Castilla Benítez <br></b></p>
  			</div>
  		</nav>
  	</footer>
  	
	<script src="{{ asset('plugins/jquery/js/jquery-3.1.1.js') }}"></script>
	<script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>
	<script src="{{ asset('plugins/chosen_1.4.2/chosen.jquery.js') }}"></script>
	<script src="{{ asset('plugins/trumbowyg/trumbowyg.js') }}"></script>
	<script src="{{ asset('plugins/trumbowyg/langs/es.min.js') }}"></script>

	@yield('js')
</body>
</html>