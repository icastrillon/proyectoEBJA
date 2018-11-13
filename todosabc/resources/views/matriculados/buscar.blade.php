@extends('layouts.app')

@section('content')
<div class="container">
	@if (session('estadoMat')==200)
	<div class="alert alert-success alert-dismissible" role="alert">
		{{ session('msgMat') }}
	</div>
	@endif
	<div class="panel panel-primary">
		<div class="panel-heading">Paso 3: Matriculados > Búsqueda de Inscritos en la Oferta {{ $oferta->nombre }}</div>
		<div class="panel-body">
			<form id="frm-buscar" action="{{ route('buscarInscrito') }}" method="POST">
				{{ csrf_field() }}
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="txtIdentificacion">IDENTIFICACION</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="txtIdentificacion" name="txtIdentificacion" placeholder="CEDULA">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="txtApellidos">APELLIDOS</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="txtApellidos" name="txtApellidos" placeholder="PRIMER APELLIDO y/o SEGUNDO APELLIDO">
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="panel-footer">
			<div>
				<a href="{{ route('buscarInscrito') }}" class="btn btn-success btn-sm" 
                    onclick="event.preventDefault(); document.getElementById('frm-buscar').submit();">
                    Buscar
                </a>
                <a href="{{ url('/matriculados') }}" class="btn btn-default btn-sm">Regresar</a> 
                <label class="control-label"> &nbsp;&nbsp;&nbsp;Nota: Debe ingresar Identificación o Apellidos para realizar la búsqueda</label>
			</div>
		</div>
	</div>

	@if (isset($encontrados) and session('estadoMat')==400 and count($encontrados) > 0)
	<div class="panel panel-warning">
		<div class="panel-heading">Registros Anteriores</div>
		<div class="panel-body">
			<table class="table table-hover table-condensed">
		    	<thead>
		    		<tr>    			
		    			<th>N°</th>
		    			<th>NOMBRES</th>
		    			<th>IDENTIFICACION</th>
		    			<th>OFERTA EDUCATIVA</th>
		    			<th>AÑO</th>
		    			<th>ZONA</th>
		    			<th>ESTADO</th>
		    		</tr>
		    	</thead>
		    	<tbody>
		    		@foreach ($encontrados as $e)
		    			<tr style="font-size: 12px;">
		    				<td scope="row">{{ $loop->index + 1 }}</td>
		    				<td>{{ $e['nombres'] }}</td>
		    				<td>{{ $e['identificacion'] }}</td>
		    				<td>{{ $e['oferta'] }}</td>
		    				<td>{{ $e['anio'] }}</td>
		    				<td>{{ $e['zona'] }}</td>
		    				<td>PROMOVIDO</td>
						</tr>
		    		@endforeach
			    </tbody>
			</table>
		</div>
	</div>
	@endif

	@if (session('estadoMat')===600)
	<div class="alert alert-danger" role="alert">
		{{ session('msgMat') }}
	</div>
	@elseif (session('estadoMat')==250)
	<div class="alert alert-info alert-dismissible" role="alert">
		{{ session('msgMat') }}
	</div>
	@endif

	@if (isset($inscritos) and $inscritos->count() > 0)
	<div class="panel panel-success">
		<div class="panel-heading">Inscritos Encontrados</div>
		<div class="panel-body">
			<table class="table table-hover table-condensed">
		    	<thead>
		    		<tr>    			
		    			<th>N°</th>
		    			<th>CODIGO</th>
		    			<th>NOMBRES</th>
		    			<th>IDENTIFICACION</th>
		    			<th>TIPO DE IDENTIFICACION</th>
		    			<th>FECHA NACIMIENTO</th>
		    			<th>OFERTA</th>
		    			<th>FECHA INSCRIPCION</th>
		    			<th></th>
		    		</tr>
		    	</thead>
		    	<tbody>
		    		@foreach ($inscritos as $insc)
		    			<tr style="font-size: 12px;">
		    				<td scope="row">{{ $loop->index + 1 }}</td>
		    				<td>{{ $insc->codigo_inscripcion }}</td>
		    				<td>{{ $insc->nombres_aspirantes }}</td>
		    				<td>{{ $insc->cedula_identidad }}</td>
		    				<td>{{ $insc->tipo_documento_identidad }}</td>
		    				<td>{{ $insc->fecha_nacimiento }}</td>
		    				<td>{{ $insc->oferta_educativa }}</td>
							<td>{{ $insc->fecha_inscripcion }}</td>
							<td>
								<a href="{{ url('matriculados/seleccionar/inscrito/'.$insc->id) }}" class="btn btn-link btn-xs">Matricular</a>
							</td>
						</tr>
		    		@endforeach
			    </tbody>
			</table>
		</div>
	</div>
	@endif
</div>
@endsection