@extends('layouts.app')

@section('content')
<div class="container">
	<div>
		@if (session('estadoDoc')===200)
			<div class="alert alert-success alert-dismissible" role="alert">
				{{ session('msgDoc') }}
	            @component('componentes/limpiar_alerta_doc')

				@endcomponent
			</div>
		@endif
		@if (isset($docentes) and $docentes->count() == 0 or session('user')->id_oferta==6 or session('user')->id_oferta==7 or session('user')->id_oferta==8 or session('user')->id_oferta==13 or session('user')->id_oferta==14 or session('user')->id_oferta==15
		or session('user')->id_oferta==20 or session('user')->id_oferta==21
		or session('user')->id_oferta==25 or session('user')->id_oferta==28
		or session('user')->id_oferta==26 or session('user')->id_oferta==27
		or session('user')->id_oferta==29 or session('user')->id_oferta==30)

		<a type="button" class="btn btn-success btn-sm" href="{{ url('docentes/nuevo') }}">Nuevo Docente</a>
		<br><br>
		@endif
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">Paso 2: Docentes > Docentes Registrados</div>
		<div class="panel-body">
		    <table class="table table-hover table-condensed table-responsive mi_tabla">
		    	<thead>
		    		<tr>
		    			<th>N°</th>
		    			<th>ZONA</th>
		    			<th>CÉDULA</th>
		    			<th>NOMBRES</th>
		    			<th>INSTITUCIÓN EDUCATIVA</th>
		    			<th>ACTIVO</th>
		    			<th>EMAIL</th>
		    			@if (session('user')->id_oferta==2)
		    			<th>TIENE VOLUNTARIO</th>
		    			@elseif (session('user')->id_oferta==10 or  session('user')->id_oferta==17 or session('user')->id_oferta==16 or session('user')->id_oferta==22 or session('user')->id_oferta==23 )
		    			<th>CLASIFICACIÓN</th>
		    			@endif
		    			<th>FECHA REGISTRO</th>
		    			<th></th>
		    		</tr>
		    	</thead>
		    	<tbody>
			    	@if (isset($docentes))
			    		@foreach ($docentes as $doc)
			    			<tr>
			    				<td scope="row">{{ $loop->index + 1 }}</td>
			    				<td>{{ $doc->zona }}</td>
			    				<td>{{ $doc->cedula }}</td>
			    				<td>{{ strtoupper($doc->apellidos) }} {{ strtoupper($doc->nombres) }}</td>
			    				<td>{{ $doc->institucion }}</td>
			    				<td>@if($doc->activo===true) SI @else NO @endif</td>
			    				<td>{{ $doc->email }}</td>
			    				@if (session('user')->id_oferta==2)
			    				<td>@if($doc->tiene_voluntario===true) SI @else NO @endif</td>
			    				@elseif (session('user')->id_oferta==10  or session('user')->id_oferta==17
			    					or session('user')->id_oferta==16   or  session('user')->id_oferta==24
			    					or session('user')->id_oferta==25 or session('user')->id_oferta==26
			    					or session('user')->id_oferta==27 or session('user')->id_oferta==28
			    					or session('user')->id_oferta==29 or session('user')->id_oferta==30)
			    				<td>{{ $doc->clasificacion }}</td>
			    				@endif
								<td>{{ $doc->fecha_registro }}</td>
								<td>
									<a href="{{ url('docentes/id/'.$doc->id) }}" class="btn btn-link btn-xs">Modificar</a>
									@if ($doc->cedula!=session('user')->usuario)
									<a href="{{ url('docentes/buscar/'.$doc->id) }}" class="btn btn-link btn-xs">Eliminar</a>
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