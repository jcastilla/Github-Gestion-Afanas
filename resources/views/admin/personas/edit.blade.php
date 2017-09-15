@extends('admin/template/main')

@section('title', 'Editar persona ' . $persona->nombre)

@section('content')

	{!! Form::open(array('route' => ['admin.personas.update',$persona->id], 'method' => 'PUT')) !!}﻿

	<div class="row justify-content-md-center">
	
			<div class="form-group col-md-3" style="width: 20%;">
				{!! Form::label('nombre', 'Nombre') !!}
				{!! Form::text('nombre', $persona->nombre, ['class' => 'form-control', 'required'] ) !!}
			</div>
			
			<div class="form-group col-md-3" style="width: 20%;">
				{!! Form::label('apellidos', 'Apellidos') !!}
				{!! Form::text('apellidos', $persona->apellidos, ['class' => 'form-control', 'required'] ) !!}
			</div>

			<div class="form-group col-md-3" style="width: 20%;">
				{!! Form::label('fechaNacimiento', 'Fecha de nacimiento') !!}
				{!! Form::text('fechaNacimiento', $persona->fechaNacimiento, ['class' => 'form-control', 'placeholder' => 'dd/mm/YYYY', 'required'] ) !!}
			</div>

			<div class="form-group col-md-3" style="width: 20%;">
				{!! Form::label('fechaIngreso', 'Fecha de ingreso') !!}
				{!! Form::text('fechaIngreso', $persona->fechaIngreso, ['class' => 'form-control', 'placeholder' => 'dd/mm/YYYY', 'required'] ) !!}
			</div>

	</div>

	<div class="container">
		<div class="row justify-content-md-center">
			<div class="panel panel-default col col-md-3 col-md-offset-1"  style="width: 10%;">
		 			<div class="panel-heading"><b>Genero</b></div>
		 				<div class="panel-body">
							<div class="radio">
							@if($persona->generoPersona == "Hombre")
						 		<label>
						    		<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						    		Hombre
						  		</label>	
							</div>
							<div class="radio">
						  		<label>
						    		<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
						    		Mujer
						  		</label>
						 	@else
						  		<label>
						    		<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
						    		Hombre
						  		</label>	
							</div>
							<div class="radio">
						  		<label>
						    		<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" checked>
						    		Mujer
						  		</label>
						  	@endif
							</div>
						</div>
				</div>


			<div class="panel panel-default col col-md-3 col-md-offset-1"  style="width: 10%;">
	 			<div class="panel-heading"><b>Regimen</b></div>
	 				<div class="panel-body">
						<div class="radio">
						@if($persona->regimenPersona == "Interno")
					 		<label>
					    		<input type="radio" name="optionsRadios1" id="optionsRadios1" value="option1" checked>
					    		Interno
					  		</label>
						</div>
						<div class="radio">
					  		<label>
					    		<input type="radio" name="optionsRadios1" id="optionsRadios2" value="option2">
					    		Externo
					  		</label>
					  	@else
					  		<label>
					    		<input type="radio" name="optionsRadios1" id="optionsRadios1" value="option1">
					    		Interno
					  		</label>
						</div>
						<div class="radio">
					  		<label>
					    		<input type="radio" name="optionsRadios1" id="optionsRadios2" value="option2" checked>
					    		Externo
					  		</label>
					  	@endif
						</div>
					</div>
			</div>
		</div>
	</div>

	<div class="form-group" style="width: 50%;">
		{!! Form::label('id_centro', 'Centro') !!}
		{!! Form::select('id_centro', $centros, $persona->centro->id, ['class' => 'form-control select-category', 'required']) !!}
	</div>

	<div class="row justify-content-md-center">	
		<div class="form-group col-md-3" style="width: 20%;">
			{!! Form::label('nif', 'NIF/DNI') !!}
			{!! Form::text('nif', $persona->nif, ['class' => 'form-control', 'placeholder' => 'AN024000001', 'required'] ) !!}
		</div>

		<div class="form-group col-md-3" style="width: 20%;">
			{!! Form::label('numSeguridadSocial', 'Nº seguridad social') !!}
			{!! Form::text('numSeguridadSocial', $persona->numSeguridadSocial, ['class' => 'form-control', 'placeholder' => 'AN024000001', 'required'] ) !!}
		</div>

		<div class="form-group col-md-3" style="width: 20%;">
			{!! Form::label('centroSalud', 'Centro de salud') !!}
			{!! Form::text('centroSalud', $persona->centroSalud, ['class' => 'form-control', 'required'] ) !!}
		</div>

		<div class="form-group col-md-3"" style="width: 20%;">
			{!! Form::label('medicoCabecera', 'Médico de cabecera') !!}
			{!! Form::text('medicoCabecera', $persona->medicoCabecera, ['class' => 'form-control', 'placeholder' => '', 'required'] ) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('orienta', 'Orientación diagnóstica (Max. 255 caracteres)') !!}
		{!! Form::textarea('orienta', $persona->orienta, ['class' => 'form-control textarea-content']) !!}
	</div>

	<div class="form-group">
		{!! Form::label('medicacion', 'Medicación actual (Max. 255 caracteres)') !!}
		{!! Form::textarea('medicacion', $persona->medicacion, ['class' => 'form-control textarea-content']) !!}
	</div>

	<div class="form-group">
		{!! Form::label('vacunacion', 'Vacunación (Max. 255 caracteres)') !!}
		{!! Form::textarea('vacunacion', $persona->vacunacion, ['class' => 'form-control textarea-content']) !!}
	</div>

	<div class="row justify-content-md-center">	
		<div class="form-group col-md-6" style="width: 20%;">
			{!! Form::label('personaContacto', 'Persona de contacto') !!}
			{!! Form::text('personaContacto', $persona->personaContacto, ['class' => 'form-control', 'required'] ) !!}
		</div>

		<div class="form-group col-md-6" style="width: 20%;">
			{!! Form::label('telContacto', 'Telefono de contacto') !!}
			{!! Form::text('telContacto', $persona->telContacto, ['class' => 'form-control', 'required'] ) !!}
		</div>
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

		$('.textarea-content').trumbowyg({
			lang: 'es',
			btns: [['formatting'], ['bold','italic','underline','strikethrough'],'btnGrp-justify', 'btnGrp-lists',['horizontalRule'], ['removeformat'], ['fullscreen'] ],
		});
	</script>


@endsection



