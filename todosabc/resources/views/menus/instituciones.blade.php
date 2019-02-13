@extends('layouts.app')

@section('content')
<div class="container">
	<div>
		@if (session('estadoIE')==200)
			<div class="alert alert-success alert-dismissible" role="alert">
				{{ session('msgIE') }}
	            @component('componentes/limpiar_alerta_ies')

				@endcomponent
			</div>
		@elseif (session('estadoIE')==600)
			<div class="alert alert-warning alert-dismissible" role="alert">
				{{ session('msgIE') }}
				@component('componentes/limpiar_alerta_ies')

				@endcomponent
			</div>
		@endif

		@if (isset($ies) and $ies->count() == 0 or session('user')->id_oferta==8 or session('user')->id_oferta==15 or session('user')->id_oferta==19 or session('user')->id_oferta==16 or session('user')->id_oferta==17  or session('user')->id_oferta==22 or  session('user')->id_oferta==23 or session('user')->id_oferta==24)
		<a type="button" class="btn btn-success btn-sm" href="{{ url('instituciones/nueva') }}">Nueva Institución</a><br><br>
		@endif
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">Paso 1: Instituciones > Instituciones Registradas</div>
		<div class="panel-body">
		    <table class="table table-hover table-condensed table-responsive">
		    	<thead>
		    		<tr>
		    			<th>N°</th>
		    			<th>ZONA</th>
		    			<th>DISTRITO</th>
		    			<th>AMIE</th>
		    			<th>INSTITUCIÓN EDUCATIVA</th>
		    			<th>FECHA REGISTRO</th>
		    			<th></th>
		    		</tr>
		    	</thead>
		    	<tbody>
			    	@if (isset($ies))
			    		@foreach ($ies as $ie)
			    			<tr>
			    				<td scope="row">{{ $loop->index + 1 }}</td>
			    				<td>{{ $amies[$ie->amie]->zona }}</td>
			    				<td>{{ $amies[$ie->amie]->distrito }} - {{ $amies[$ie->amie]->nombre_distrito }}</td>
			    				<td>{{ $ie->amie }}</td>
			    				<td>{{ $amies[$ie->amie]->institucion }}</td>
								<td>{{ $ie->fecha_registro }}</td>
								<td>
									<a href="{{ url('instituciones/id/'.$ie->id) }}" class="btn btn-link btn-xs">Modificar</a>
									<a href="{{ url('instituciones/seleccionar/'.$ie->id) }}" class="btn btn-link btn-xs">Eliminar</a>
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