@extends('layouts.app')

@section('content')
<div class="container">
	<div class="panel panel-primary">		
		<div class="panel-heading">Certificados</div>
		<div class="panel-body">			
		    <table class="table table-hover table-condensed table-responsive">
		    	<thead>
		    		<tr>    			
		    			<th>N°</th>
		    			<th>NOMBRES</th>
		    			<th>IDENTIFICACIÓN</th>
		    			<th>FECHA NACIMIENTO</th>
		    			<th>OFERTA</th>
		    			<th>INSTITUCIÓN EDUCATIVA</th>
		    			<th>ASISTENCIA</th>
		    			@if (session('user')->id_oferta==2 or session('user')->id_oferta==10)
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
			    				<td>{{ $mat->fecha_nacimiento }}</td>
			    				<td>{{ $mat->oferta_educativa }}</td>
			    				<td>{{ $mat->amie }} - {{ $mat->institucion }}</td>
			    				<td>@if ($mat->asiste_con_frecuencia==false) Desertó @else Asiste con frecuencia @endif</td>
			    				@if (session('user')->id_oferta==2 or session('user')->id_oferta==10 or session('user')->id_oferta==16)
								<td>{{ $mat->fecha_matriculacion }}</td>
								@else
								<td>{{ $mat->paralelo }}</td>
								@endif
								<td>
									@if($mat->asiste_con_frecuencia==true and in_array($mat->id_oferta, $descarga_visible))
										<a href="{{ route('verificar', ['id_mat' => $mat->id]) }}" class="btn btn-link btn-xs">Promoción</a>
									@endif
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