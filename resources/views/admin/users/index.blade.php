@extends('admin/template/main')

@section('title', 'Lista de usuarios')

@section('content')
	<a href="{{ route('admin.users.create') }}" class="btn btn-info">Registrar nuevo usuario</a>
	
	<!-- BUSCADOR DE USUARIOS -->
	{!! Form::open(['route' => 'admin.users.index', 'method' => 'GET', 'class' => 'navbar-form pull-right']) !!}
		<div class="input-group">
			{!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Buscar usuario..', 'aria-describedby' => 'search']) !!}
			<span class="input-group-addon" id="search"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
		</div>
	{!! Form::close() !!}
	<!-- FIN DEL BUSCADOR -->

	<hr>
	<table class="table table-striped">
		<thead>
			<th>Nombre</th>
			<th>Email</th>
			<th>Tipo</th>
			<th>Acción</th>
		</thead>
		<tbody>
			@foreach($users as $user)
				<tr>
					<td>{{ $user->name }}</td>
					<td>{{ $user->email }}</td>
					<td>
						@if($user->type == "admin")
							<span class="label label-danger">{{ $user->type }}</span>
						@else
							<span class="label label-primary">{{ $user->type }}</span>
						@endif
					</td>
					<td>

					<a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span></a>

					<a href="{{ route('admin.users.destroy', $user->id) }}" onclick="return confirm('¿Seguro que quieres eliminar el usuario?')" class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a></td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<div class="text-center">
		{!! $users->render() !!}
	</div>
@endsection