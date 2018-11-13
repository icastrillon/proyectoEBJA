@extends('layouts.app')

@section('content')
<div class="container-fluid">
	@if(session('estadoK')==400)
		<div class="alert alert-danger alert-dismissible" role="alert">
			Falta completar el registro de calificaciones
		</div>
	@endif
	<div class="panel panel-primary">
		<div class="panel-heading">Parámetros de {{ session('oferta')->nombre }}</div>
		<div class="alert alert-info alert-dismissible" role="alert">
			<p style="font-weight: bold;">RECOMENDACIÓN:</p>
			<p style="text-align: justify;"><em>Antes de ingresar las calificaciones, verifique que las asignaturas, docentes y paralelos estén correctos.</em> Para modificar cualquier relación docente-materia-paralelo debe solicitar mediante correo a planta central, <span style="color: red;">la solicitud implica que se borren las calificaciones de la relación reportada.</span></p>
		</div>
		<div class="panel-body">
			<form class="form-inline" method="GET" action="{{ route('calificaciones_basica') }}">
				{{ csrf_field() }}
				<div class="form-group">
					<label for="sel_asignatura">Asignatura:</label>
					<select id="sel_asignatura" name="bloque" class="form-control" style="width: 250px; font-size: 10px;">
						@foreach($materias as $materia)
						<option value="{{ $materia->id }}">{{ strtoupper($materia->asignatura) }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="sel_docente">Docente:</label>
					<select id="sel_docente" name="doc" class="form-control" style="width: 400px; font-size: 10px;">
						@foreach($docentes as $docente)
						<option value="{{ $docente->id }}">{{ strtoupper($docente->apellidos) }} {{ strtoupper($docente->nombres) }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="sel_paralelo">Paralelo:</label>
					<select id="sel_paralelo" name="par" class="form-control" style="width: 70px; font-size: 10px;">
						@foreach($paralelos as $p)
						<option value="{{ $p->paralelo }}">{{ $p->paralelo }}</option>
						@endforeach
					</select>
				</div>
				<hr>
				<div class="panel panel-success">
					<div class="panel-heading">Docentes / Asignaturas / Paralelos</div>
					<div class="panel-body">
						<table class="table table-hover table-condensed table-responsive">
							<thead>
				    		<tr>    			
				    			<th>N°</th>
				    			<th>INSTITUCIÓN EDUCATIVA</th>
				    			<th>DOCENTE</th>
				    			<th>ASIGNATURA</th>
				    			<th>PARALELO</th>
				    		</tr>
		    				</thead>
		    				<tbody>
		    					@foreach($docentes_asignaturas as $doc)
		    						<tr>
		    							<td>{{ $loop->index + 1 }}</td>
		    							<td>{{ $doc->institucion }}</td>
		    							<td>{{ $doc->docente }}</td>
		    							<td>{{ strtoupper($doc->asignatura) }}</td>
		    							<td>{{ $doc->paralelo }}</td>
		    						</tr>
		    					@endforeach
		    				</tbody>
						</table>
					</div>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-success" value="Cargar Calificaciones"></input>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection