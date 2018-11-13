@extends('layouts.app')

@section('content')
<div class="container">
	<div>		
		@if (session('estadoMat')==200)
			<div class="alert alert-success alert-dismissible" role="alert">
				{{ session('msgMat') }}
				@component('componentes/limpiar_alerta_mat')

				@endcomponent
			</div>
		@endif
		<a type="button" class="btn btn-success btn-sm" href="{{ route('nuevoMatriculado') }}">Nuevo Matriculado</a>
	</div>
	<br/>
	<div class="panel panel-primary">		
		<div class="panel-heading">Paso 3: Matriculados > Participantes Matriculados</div>
		<div class="panel-body">			
		    <table class="table table-hover table-condensed table-responsive mi_tabla">
		    	<thead>
		    		<tr>    			
		    			<th>N°</th>
		    			<th>NOMBRES</th>
		    			<th>IDENTIFICACIÓN</th>
		    			<th>TIPO DE IDENTIFICACION</th>
		    			<th>FECHA NACIMIENTO</th>
		    			<th>OFERTA</th>
		    			<th>INSTITUCIÓN EDUCATIVA</th>
		    			<th>ASISTENCIA</th>
		    			@if (session('user')->id_oferta==2)
		    			<th>FECHA MATRICULACIÓN</th>
		    			@else
						<th>PARALELO</th>
						@endif
		    			<th></th>
		    		</tr>
		    	</thead>
		    	<tbody>
			    	@if (isset($matriculados))
			    		@foreach ($matriculados as $mat)
			    			<tr style="font-size: 12px;">
			    				<td scope="row">{{ $loop->index + 1 }}</td>
			    				<td>{{ $mat->nombres_aspirantes }}</td>
			    				<td>{{ $mat->cedula_identidad }}</td>
			    				<td>{{ $mat->tipo_documento_identidad }}</td>
			    				<td>{{ $mat->fecha_nacimiento }}</td>
			    				<td>{{ $mat->oferta_educativa }}</td>
			    				<td>{{ $mat->amie }} - {{ $mat->institucion }}</td>
			    				<td>@if ($mat->asiste_con_frecuencia==false) Desertó @else Asiste con frecuencia @endif</td>
			    				@if (session('user')->id_oferta==2)
								<td>{{ $mat->fecha_matriculacion }}</td>
								@else
								<td>{{ $mat->paralelo }}</td>
								@endif
								<td>
									<a href="{{ url('matriculados/seleccionar/modificar/'.$mat->id) }}" class="btn btn-link btn-xs">Modificar</a>
									<a href="{{ url('matriculados/seleccionar/eliminar/'.$mat->id) }}" class="btn btn-link btn-xs">Eliminar</a>
								</td>
							</tr>
			    		@endforeach
			    	@endif
			    </tbody>
			</table>
		</div>
	</div>    
</div>
@endsection