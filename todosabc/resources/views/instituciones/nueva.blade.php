@extends('layouts.app')

@section('content')
<div class="container">
	@if (session('estadoIE')==500)
	<div class="alert alert-danger alert-dismissible" role="alert">
		{{ session('msgIE') }}
	</div>
	@elseif (session('estadoIE')==600)
	<div class="alert alert-info alert-dismissible" role="alert">
		{{ session('msgIE') }}
	</div>
	@endif
		<div class="panel panel-primary">
			<div class="panel-heading">Paso 1: Instituciones > Nueva Institución</div>
			<div class="panel-body">
				<div class="form-horizontal">
					<div class="form-group">
					    <label for="lblZona" class="col-sm-3 control-label">ZONA</label>
					    <div class="col-sm-3">
					      <span class="form-control" id="lblZona">{{ session('user')->zona }}</span>
					    </div>
					</div>
					</div>
				@if ($amie)
				<div class="form-horizontal">
					<div class="form-group">
					    <label for="ie" class="col-sm-3 control-label">INSTITUCIÓN EDUCATIVA</label>
					    <div class="col-sm-8">
					    	<span style="font-size: 12px;" class="form-control" id="ie">{{ $amie->amie }} - {{ $amie->distrito }} - {{ $amie->nombre_distrito }} - {{ $amie->institucion }}</span>
					    </div>
					</div>
				</div>
			@if(session('user')->id_oferta==22 or session('user')->id_oferta==23  or session('user')->id_oferta==24 or session('user')->id_oferta==25 or session('user')->id_oferta==26 or session('user')->id_oferta==27 or session('user')->id_oferta==28 or session('user')->id_oferta==29 or session('user')->id_oferta==30 )
				<div class="form-horizontal">
					<div class="form-group">
				<form id="frm-guardar" action="{{ route('guardarIE') }}" method="POST">
				{{ csrf_field() }}
						<label class="col-sm-3 control-label" id="cpl">CPL </label>
							<div class="col-sm-2">
				              <select name="id_cpl" id="id_cpl" class="form-control">
				              <option value="-1">--Seleccionar--</option>
				                @foreach($cpls as $cpl)
				                <option value="{{$cpl->id }}">{{$cpl->id}}-{{$cpl->nombre}}</option>
				              @endforeach
				            </select>
				       </div>
				       <div class="form-horizontal">
						<div class="form-group">
						    <label for="lblFecha" class="col-sm-3 control-label">FECHA REGISTRO</label>
						    <div class="col-sm-3">
						      <span class="form-control" id="lblFecha">{{ $ie->fecha_registro }}</span>
						    </div>
						</div>
					</div>
					<input type="hidden" name="selectAmie" value="{{ $ie->amie }}">
				</form>
				</div>
				</div>
			@else
			<div class="form-horizontal">
				<div class="form-group">
				<form id="frm-guardar" action="{{ route('guardarIE') }}" method="POST">
				{{ csrf_field() }}
					 <div class="form-horizontal">
						<div class="form-group">
						    <label for="lblFecha" class="col-sm-3 control-label">FECHA REGISTRO</label>
						    <div class="col-sm-3">
						      <span class="form-control" id="lblFecha">{{ $ie->fecha_registro }}</span>
						    </div>
						</div>
					</div>
					<input type="hidden" name="selectAmie" value="{{ $ie->amie }}">
				</form>
					</div>
				</div>
		@endif
	@endif

		<div class="form-horizontal">
					<div class="form-group">
					    <label for="frm-buscar" class="col-sm-3 control-label">AMIE</label>
					    <div class="col-sm-2">
			    			<form id="frm-buscar" action="{{ route('buscarAmie') }}" method="POST">
			    			{{ csrf_field() }}
			    				<input class="form-control" type="text" name="cod_amie" value="" onkeyup="this.value = this.value.toUpperCase();" maxlength="10">
			    				<input type="hidden" name="accion" value="nue_ie">
			            	</form>
			            </div>
					     <div class="col-sm-1">
							<a href="{{ route('buscarAmie') }}" class="btn btn-success"
			                    onclick="event.preventDefault(); document.getElementById('frm-buscar').submit();">
			                    Buscar
			                </a>
		            	</div>
					</div>
			</div>
	</div>
		<div class="panel-footer">
				<a href="{{ route('guardarIE') }}" class="btn btn-primary btn-sm"
				 onclick="event.preventDefault(); document.getElementById('frm-guardar').submit();">
                    Guardar Institución
                </a>
                <a href="{{ url('/instituciones') }}" class="btn btn-default btn-sm">Cancelar</a>
		</div>
	</div>
</div>
@endsection