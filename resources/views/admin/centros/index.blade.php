@extends('admin/template/main')

@section('title', 'Lista de centros')

@section('content')
	<a href="{{ route('admin.centros.create') }}" class="btn btn-info">Registrar nuevo centro</a>
	
	<!-- BUSCADOR DE CENTROS -->
	{!! Form::open(['route' => 'admin.centros.index', 'method' => 'GET', 'class' => 'navbar-form pull-right']) !!}
		<div class="input-group">
			{!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Buscar centro..', 'aria-describedby' => 'search']) !!}
			<span class="input-group-addon" id="search"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
		</div>
	{!! Form::close() !!}
	<!-- FIN DEL BUSCADOR -->

	<hr>
	<table class="table table-striped">
		<thead>
			<th>Nombre</th>
			<th>Dirección</th>
			<th>Población</th>
			<th>Acción</th>
		</thead>
		<tbody>
			@foreach($centros as $centro)
				<tr>
					<td>{{ $centro->nombre }}</td>
					<td>{{ $centro->direccion }}</td>
					<td>{{ $centro->poblacion }}</td>
					<td>

					<a href="{{ route('admin.centros.edit', $centro->id) }}" class="btn btn-warning"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span></a>

					<a href="{{ route('admin.centros.destroy', $centro->id) }}" onclick="return confirm('¿Seguro que quieres eliminar el centro?')" class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a></td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<div class="text-center">
		{!! $centros->render() !!}
	</div>
@endsection