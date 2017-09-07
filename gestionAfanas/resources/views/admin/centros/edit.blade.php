@extends('admin/template/main')

@section('title', 'Editar centro ' . $centro->nombre)

@section('content')

	{!! Form::open(array('route' => ['admin.centros.update',$centro->id], 'method' => 'PUT')) !!}﻿

		<div class="form-group">
			{!! Form::label('nombre', 'Nombre') !!}
			{!! Form::text('nombre', $centro->nombre, ['class' => 'form-control', 'placeholder' => 'Nombre del centro' ,'required']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('direccion', 'Dirección') !!}
			{!! Form::text('direccion', $centro->direccion, ['class' => 'form-control', 'placeholder' => 'Dirección' ,'required']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('poblacion', 'Población') !!}
			{!! Form::text('poblacion', $centro->poblacion, ['class' => 'form-control', 'placeholder' => 'Población' ,'required']) !!}
		</div>

		<div class="form-group">
			<a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
			{!! Form::submit('Editar', ['class' => 'btn btn-primary']) !!}
		</div>

	{!! Form::close() !!}
@endsection