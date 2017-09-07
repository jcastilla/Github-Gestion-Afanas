@extends('admin/template/main')

@section('title', 'Lista de personas')

@section('content')
	<a href="{{ route('admin.personas.create') }}" class="btn btn-info">Registrar nueva persona</a>
	
	<!-- BUSCADOR DE PERSONAS -->
	{!! Form::open(['route' => 'admin.personas.index', 'method' => 'GET', 'class' => 'navbar-form pull-right']) !!}
		<div class="input-group">
			{!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Buscar persona..', 'aria-describedby' => 'search']) !!}
			<span class="input-group-addon" id="search"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
		</div>
	{!! Form::close() !!}
	<!-- FIN DEL BUSCADOR -->

	<hr>
	<table class="table table-striped">
		<thead>
			<th>NIF</th>
			<th>Centro</th>
			<th>Nombre</th>
			<th>Apellidos</th>
			<th>Fecha de ingreso</th>
			<th>Regimen</th>		
			<th>Médico de cabecera</th>
			<th>Acción</th>
		</thead>
		<tbody>
			@foreach($personas as $persona)
				<tr>
					<td>{{ $persona->nif }}</td>
					<td>{{ $persona->centro->nombre }}</td>
					<td>{{ $persona->nombre }}</td>
					<td>{{ $persona->apellidos }}</td>
					<td>{{ $persona->fechaIngreso }}</td>
					<td>{{ $persona->regimenPersona }}</td>					
					<td>{{ $persona->medicoCabecera }}</td>
					<td>

					<a href="{{ route('admin.personas.show', $persona->id) }}" class="btn btn-success"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></a>

					<a href="{{ route('admin.personas.edit', $persona->id) }}" class="btn btn-warning"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span></a>

					<a href="{{ route('admin.personas.destroy', $persona->id) }}" onclick="return confirm('¿Seguro que quieres eliminar la persona?')" class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a></td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<div class="text-center">
		{!! $personas->render() !!}
	</div>
@endsection