@extends('layouts.app')

@section('content')
<div class="container">
	<div class="panel panel-primary">		
		<div class="panel-heading">Verificación del estado del estudiante</div>
		<div class="panel-body">
			<center>
				<h2>{{ $matriculado[0]->nombres_aspirantes }}</h2>
				<h3>CI: {{ $matriculado[0]->cedula_identidad }}</h3>
				<h3>{{ $matriculado[0]->institucion }}</h3>
				<h3>{{ $matriculado[0]->oferta }}</h3>
				@if($ok==1)
				<div class="alert alert-success" role="alert" style="width: 400px;">
					<h3><a href="{{ route('certificar', ['id_mat' => $matriculado[0]->id, 'amie' => $matriculado[0]->amie]) }}">Certificado de Promoción (PDF)</a></h3>
				</div>
				@elseif($ok==2)
				<div class="alert alert-warning" role="alert">
					<h4>El estudiante NO tiene todas las calificaciones registradas</h4>
					<a href="{{ route('certificados') }}" style="font-size: 16px; font-weight: bold;">Continuar</a>
				</div>
				@elseif($ok==3)
				<div class="alert alert-danger" role="alert">
					<h4>El estudiante NO se encuentra PROMOVIDO</h4>
					<a href="{{ route('certificados') }}" style="font-size: 16px; font-weight: bold;">Continuar</a>
				</div>
				@else
				<div class="alert alert-danger" role="alert">
					<h4>El estudiante NO se encuentra PROMOVIDO en: {{ $asignaturas_no_aprobadas }}</h4>
					<a href="{{ route('certificados') }}" style="font-size: 16px; font-weight: bold;">Continuar</a>
				</div>
				@endif		
			</center>
		</div>
	</div>
</div>
@endsection