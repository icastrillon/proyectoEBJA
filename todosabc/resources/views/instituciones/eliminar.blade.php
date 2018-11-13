@extends('layouts.app')

@section('content')
<div class="container">
	<div class="panel panel-primary">
		<div class="panel-heading">Paso 1: Instituciones > Institución Educativa Seleccionada</div>
		<div class="panel-body">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="identificacion">AMIE</label>
					<div class="col-sm-5">
						<span class="form-control lbl" id="identificacion">{{ $ie->amie }}</span>
					</div>
				</div>
			</div>
			<div class="form-horizontal">
				<div class="form-group ">
					<label class="col-sm-4 control-label" for="nombres">INSTITUCIÓN</label>
					<div class="col-sm-5">
						<span class="form-control lbl" id="nombres">{{ $institucion }}</span>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<div>
				<a href="{{ route('eliminarDoc') }}" class="btn btn-danger btn-sm" 
                    onclick="event.preventDefault(); document.getElementById('frm-eliminar').submit();">
                    Eliminar Institución
                </a>

                <a href="{{ route('institucionesUsuario') }}" class="btn btn-default btn-sm">Cancelar</a>                    

                <form id="frm-eliminar" action="{{ route('eliminarIE') }}" method="POST" style="display: none;">
                	<input type="hidden" name="id" value="{{ $ie->id }}">
                    {{ csrf_field() }}
                </form>
			</div>
		</div>
	</div>
</div>
@endsection