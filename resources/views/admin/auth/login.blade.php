@extends('admin.template.main')

@if(Auth::guest())

@section('title', 'Iniciar sesión')

@section('content')
	{!! Form::open(['route' => 'admin.auth.login', 'method' => 'POST']) !!}

	<div class="container">
		<div class="row">
    		<div class="col-md-8 col-md-offset-2">
    			<div class="panel panel-default">

			    	<div class="panel-body">
						<div class="form-group">
							{!! Form::label('email', 'Email') !!}
							{!! Form::email('email', null, ['class' => 'form-control']) !!}
						</div>
						<div class="form-group">
							{!! Form::label('password', 'Contraseña') !!}
							{!! Form::password('password', ['class' => 'form-control']) !!}
						</div>
						<div class="form-group">
							{!! Form::submit('Acceder', ['class' => 'btn btn-primary']) !!}
						</div>
						<a class="btn btn-link" href="{{ url('password/reset') }}">¿Olvidaste la contraseña?</a>
					</div>
	
				</div>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
@endsection

@endif