@extends('layouts.app')

@section('content')
<div class="container">
	@if(session('estadoK')==400)
		<div class="alert alert-danger alert-dismissible" role="alert">
			Falta completar el registro de calificaciones
		</div>
	@endif
	<div class="panel panel-primary">		
		<div class="panel-heading">Fases educativas de los estudiantes matriculados</div>
		<div class="panel-body">	
			<form id="frm_guardar_fases" class="form-horizontal" method="POST" action="{{ route('bachilleratoFases') }}">
			{{ csrf_field() }}		
		    <table class="table table-hover table-condensed table-responsive">
		    	<thead>
		    		<tr>    			
		    			<th>N°</th>
		    			<th>NOMBRES</th>
		    			<th>IDENTIFICACIÓN</th>
		    			<th>INSTITUCIÓN EDUCATIVA</th>
		    			<th>PARALELO</th>
		    			<th>1 BGU</th>
		    			<th>2 BGU</th>
		    			<th>3 BGU</th>		    			
		    			
		    		</tr>
		    	</thead>
		    	<tbody>						
		    		@foreach ($matriculados as $mat)
		    			<tr style="font-size: 12px;">			    				
		    				<td scope="row">{{ $loop->index + 1 }}</td>
		    				<td>{{ $mat->nombres_aspirantes }}</td>
		    				<td>{{ $mat->cedula_identidad }}</td>
		    				<td>{{ $mat->amie }} - {{ $mat->institucion }}</td>
		    				<td>{{ $mat->paralelo }}</td>
		    				<td>
		    					@if ($mat->ultimo_anio_aprobado=='10 EGB' or $mat->ultimo_anio_aprobado=='Documento 10mo. EGB')

		    					<input type="checkbox" name="f1-{{$mat->codigo_inscripcion}}" id="f1-{{$mat->codigo_inscripcion}}" checked="checked" >
		    					@endif

		    					</td>

		    				<td>
		    					@if ($mat->ultimo_anio_aprobado=='10 EGB' or $mat->ultimo_anio_aprobado=='Documento 10mo. EGB' 
		    					or $mat->ultimo_anio_aprobado=='1 BGU' or $mat->ultimo_anio_aprobado=='Documento 1ro. BGU' )  

		    					<input type="checkbox" name="f2-{{$mat->codigo_inscripcion}}" id="f2-{{$mat->codigo_inscripcion}}"  checked="checked" >	
		    					@endif
		    				</td>
		    				<td>
		    					@if ($mat->ultimo_anio_aprobado=='10 EGB' or $mat->ultimo_anio_aprobado=='Documento 10mo. EGB' or $mat->ultimo_anio_aprobado=='1 BGU' or 
		    					$mat->ultimo_anio_aprobado=='Documento 1ro. BGU' or
		    					$mat->ultimo_anio_aprobado=='2 BGU' 
		    					or $mat->ultimo_anio_aprobado=='Documento 2do. BGU' ) 
		    					<input type="checkbox" name="f3-{{$mat->codigo_inscripcion}}" id="f3-{{$mat->codigo_inscripcion}}"  checked="checked">
		    					@endif
		    				</td>
						</tr>
		    		@endforeach			    				    	
			    </tbody>
			</table>
		</form>
			@if (count($matriculados) > 0)
				<button type="button" class="btn btn-primary" onclick="guardar_fases(event);">Continuar</button>
			@endif
		</div>
	</div>    
</div>
@endsection