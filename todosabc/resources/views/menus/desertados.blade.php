@extends('layouts.app')

@section('content')
<div class="container">
	<div class="panel panel-primary">		
		<div class="panel-heading">Participantes Desertados</div>
		<div class="panel-body">
			<form id="frm_razones" method="POST" action="{{ route('matriculados_desertados') }}">	
			{{ csrf_field() }}		
			    <table class="table table-hover table-condensed table-responsive mi_tabla">
			    	<thead>
			    		<tr>    			
			    			<th>N°</th>
			    			<th>NOMBRES</th>
			    			<th>IDENTIFICACIÓN</th>
			    			<th>TIPO DE IDENTIFICACION</th>
			    			<th>FECHA NACIMIENTO</th>
			    			<th>INSTITUCIÓN EDUCATIVA</th>
			    			@if (session('user')->id_oferta!=2)
			    			<th>PARALELO</th>
							@endif
			    			<th>RAZÓN DE DESERCIÓN</th>
			    		</tr>
			    	</thead>
			    	<tbody>
				    	@if (isset($desertados))
				    		@foreach ($desertados as $mat)
				    			<tr style="font-size: 12px;">
				    				<td scope="row">{{ $loop->index + 1 }}</td>
				    				<td>{{ $mat->nombres_aspirantes }}</td>
				    				<td>{{ $mat->cedula_identidad }}</td>
				    				<td>{{ $mat->tipo_documento_identidad }}</td>
				    				<td>{{ $mat->fecha_nacimiento }}</td>
				    				<td>{{ $mat->amie }} - {{ $mat->institucion }}</td>
				    				@if (session('user')->id_oferta!=2)
									<td>{{ $mat->paralelo }}</td>
									@endif
									<td>
										<select id="rd-{{ $mat->codigo_inscripcion }}" name="rd-{{ $mat->codigo_inscripcion }}" value="{{ $mat->razon_desercion }}" onchange="marcar_sin_razon_desercion(this);">
											<option value="-1">--seleccionar--</option>
											<option @if($mat->razon_desercion=='Accede a otra oferta educativa') selected="selected" @endif value="Accede a otra oferta educativa">Accede a otra oferta educativa</option>
											<option @if($mat->razon_desercion=='Adiccion alcohol o drogas') selected="selected" @endif value="Adiccion alcohol o drogas">Adiccion alcohol o drogas</option>
											<option @if($mat->razon_desercion=='Avanzada edad') selected="selected" @endif value="Avanzada edad">Avanzada edad</option>
											<option @if($mat->razon_desercion=='Bullying - Acoso escolar') selected="selected" @endif value="Bullying - Acoso escolar">Bullying - Acoso escolar</option>
											<option @if($mat->razon_desercion=='Cambio de domicilio o migracion') selected="selected" @endif value="Cambio de domicilio o migracion">Cambio de domicilio o migracion</option>
											<option @if($mat->razon_desercion=='Debe cuidar a hijos pequenios') selected="selected" @endif value="Debe cuidar a hijos pequenios">Debe cuidar a hijos pequenios</option>
											<option @if($mat->razon_desercion=='Discapacidad severa') selected="selected" @endif value="Discapacidad severa">Discapacidad severa</option>
											<option @if($mat->razon_desercion=='Discriminacion de genero') selected="selected" @endif value="Discriminacion de genero">Discriminacion de genero</option>
											<option @if($mat->razon_desercion=='Domilicio distante a la oferta') selected="selected" @endif value="Domilicio distante a la oferta">Domilicio distante a la oferta</option>
											<option @if($mat->razon_desercion=='Enfermedad') selected="selected" @endif value="Enfermedad">Enfermedad</option>
											<option @if($mat->razon_desercion=='Factores Economicos') selected="selected" @endif value="Factores Economicos">Factores Economicos</option>
											<option @if($mat->razon_desercion=='Falta de interes') selected="selected" @endif value="Falta de interes">Falta de interes</option>
											<option @if($mat->razon_desercion=='Falta de permiso laboral') selected="selected" @endif value="Falta de permiso laboral">Falta de permiso laboral</option>
											<option @if($mat->razon_desercion=='Problemas de Transporte') selected="selected" @endif value="Problemas de Transporte">Problemas de Transporte</option>
											<option @if($mat->razon_desercion=='Problemas familiares') selected="selected" @endif value="Problemas familiares">Problemas familiares</option>
										</select>
									</td>
								</tr>
				    		@endforeach
				    	@endif
				    </tbody>
				</table>
			</form>
			@if (count($desertados) > 0)
				<button id="btn_razones" type="button" class="btn btn-primary" onclick="document.getElementById('frm_razones').submit();">Continuar</button>
			@endif
		</div>
	</div>    
</div>
@endsection