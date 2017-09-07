@extends('admin/template/main')

@section('title', 'Editar usuario ' . $user->name)

@section('content')
	
	{!! Form::open(array('route' => ['admin.users.update',$user->id], 'method' => 'PUT')) !!}﻿

	<div class="form-group">
		{!! Form::label('name', 'Nombre') !!}
		{!! Form::text('name', $user->name, ['class' => 'form-control', 'placeholder' => 'Nombre completo' ,'required']) !!}
	</div>

	<div class="form-group">
		{!! Form::label('email', 'Email') !!}
		{!! Form::email('email', $user->email, ['class' => 'form-control', 'placeholder' => 'example@gmail.com' ,'required']) !!}
	</div>

	<div class="form-group">
		{!! Form::label('type', 'Tipo') !!}
		{!! Form::select('type', ['member' => 'Miembro', 'admin' => 'Administrador'], $user->type, ['class' => 'form-control select-category', 'required']) !!}
	</div>


	<div class="form-group">
		<a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
		{!! Form::submit('Editar', ['class' => 'btn btn-primary']) !!}
	</div>

	{!! Form::close() !!}

@endsection

@section('js')
	<script>
		$(".select-category").chosen({
			
		});
	</script>
@endsection