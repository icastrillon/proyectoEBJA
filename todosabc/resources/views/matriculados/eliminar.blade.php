@extends('layouts.app')

@section('content')
<div class="container">
	<div class="panel panel-primary">
		<div class="panel-heading">Paso 3: Matriculados > Matriculado Seleccionado</div>
		<div class="panel-body">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="identificacion">IDENTIFICACIÓN</label>
					<div class="col-sm-5">
						<span class="form-control lbl" id="identificacion">{{ $mat->cedula_identidad }}</span>
					</div>
				</div>
			</div>
			<div class="form-horizontal">
				<div class="form-group ">
					<label class="col-sm-4 control-label" for="nombres">NOMBRES</label>
					<div class="col-sm-5">
						<span class="form-control lbl" id="nacimiento">{{ $mat->nombres_aspirantes }}</span>
					</div>
				</div>
			</div>
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="nacimiento">FECHA NACIMIENTO</label>
					<div class="col-sm-5">
						<span class="form-control lbl" id="nacimiento">{{ $mat->fecha_nacimiento }}</span>						
					</div>
				</div>
			</div>
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="nacimiento">FECHA MATRICULACIÓN</label>
					<div class="col-sm-5">
						<span class="form-control lbl" id="nacimiento">{{ $mat->fecha_matriculacion }}</span>						
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<div>
				<a href="{{ route('eliminarMat') }}" class="btn btn-danger btn-sm" 
                    onclick="event.preventDefault(); document.getElementById('frm-eliminar').submit();">
                    Eliminar Matriculado
                </a>

                <a href="{{ route('matriculadosUsuario') }}" class="btn btn-default btn-sm">Cancelar</a>                    

                <form id="frm-eliminar" action="{{ route('eliminarMat') }}" method="POST" style="display: none;">
                	<input type="hidden" name="id" value="{{ $mat->id }}">
                	<input type="hidden" name="cod" value="{{ $mat->codigo_inscripcion }}">
                    {{ csrf_field() }}
                </form>
			</div>
		</div>
	</div>
</div>
@endsection