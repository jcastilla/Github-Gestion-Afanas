@extends('admin/template/main')

@section('title', 'Crear usuario')

@section('content')

	{!! Form::open(['route' => 'admin.users.store', 'method' => 'POST']) !!}

		<div class="form-group">
			{!! Form::label('name', 'Nombre') !!}
			{!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre completo' ,'required']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('email', 'Email') !!}
			{!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'ejemplo@hotmail.com' ,'required']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('password', 'Contraseña') !!}
			{!! Form::password('password', ['class' => 'form-control', 'placeholder' => '***********' ,'required']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('type', 'Tipo') !!}
			{!! Form::select('type', ['member' => 'Miembro', 'admin' => 'Administrador'], null, ['class' => 'form-control select-category', 'required']) !!}
		</div>

	
		<div class="form-group">
			<a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
			{!! Form::submit('Registrar', ['class' => 'btn btn-primary']) !!}
		</div>

	{!! Form::close() !!}
@endsection

@section('js')
	<script>
		$(".select-category").chosen({
			
		});
	</script>
@endsection