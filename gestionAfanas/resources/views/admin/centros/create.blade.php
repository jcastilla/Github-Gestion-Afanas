@extends('admin/template/main')

@section('title', 'Crear centro')

@section('content')

	{!! Form::open(['route' => 'admin.centros.store', 'method' => 'POST']) !!}

		<div class="form-group">
			{!! Form::label('nombre', 'Nombre') !!}
			{!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Nombre del centro' ,'required']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('direccion', 'Dirección') !!}
			{!! Form::text('direccion', null, ['class' => 'form-control', 'placeholder' => 'Dirección' ,'required']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('poblacion', 'Población') !!}
			{!! Form::text('poblacion', null, ['class' => 'form-control', 'placeholder' => 'Población' ,'required']) !!}
		</div>

	
		<div class="form-group">
			<a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
			{!! Form::submit('Registrar', ['class' => 'btn btn-primary']) !!}
		</div>

	{!! Form::close() !!}
@endsection