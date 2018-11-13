@extends('layouts.app')

@section('content')
<div class="container">
	<div class="panel panel-primary">
		<div class="panel-heading">Paso 2: Docentes > Docente Seleccionado</div>
		<div class="panel-body">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="identificacion">IDENTIFICACIÓN</label>
					<div class="col-sm-5">
						<span class="form-control lbl" id="identificacion">{{ $doc->cedula }}</span>
					</div>
				</div>
			</div>
			<div class="form-horizontal">
				<div class="form-group ">
					<label class="col-sm-4 control-label" for="nombres">NOMBRES</label>
					<div class="col-sm-5">
						<span class="form-control lbl" id="nombres">{{ $doc->apellidos }} {{ $doc->nombres }}</span>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<div>
				<a href="{{ route('eliminarDoc') }}" class="btn btn-danger btn-sm" 
                    onclick="event.preventDefault(); document.getElementById('frm-eliminar').submit();">
                    Eliminar Docente
                </a>

                <a href="{{ route('docentesUsuario') }}" class="btn btn-default btn-sm">Cancelar</a>                    

                <form id="frm-eliminar" action="{{ route('eliminarDoc') }}" method="POST" style="display: none;">
                	<input type="hidden" name="id" value="{{ $doc->id }}">
                    {{ csrf_field() }}
                </form>
			</div>
		</div>
	</div>
</div>
@endsection