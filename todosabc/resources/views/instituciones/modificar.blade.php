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
			<div class="panel-heading">Paso 1: Instituciones > Institución Seleccionada</div>

			<div class="panel-body">

				<div class="form-horizontal">
					<div class="form-group">
			@if(session('user')->id_oferta==22 or session('user')->id_oferta==23  or session('user')->id_oferta==24 or session('user')->id_oferta==25 or session('user')->id_oferta==26 or session('user')->id_oferta==27 or session('user')->id_oferta==28 or session('user')->id_oferta==29 or session('user')->id_oferta==30 )

			 <div class="alert alert-info alert-dismissible" role="alert">
			<p style="font-weight: bold;">RECOMENDACIÓN:</p>
			<p style="text-align: justify;"><em>Verifique que el CPl corresponda con la institución, si no corresponde eliminar la institución y volver a registrar.<span style="color: red;">Verificar antes de relacionar docente y  matriculados.</span></p>
			</div>
			@endif
					    <label for="lblZona" class="col-sm-3 control-label">ZONA</label>
					    <div class="col-sm-3">
					      <span class="form-control" id="lblZona">{{ session('user')->zona }}</span>
					    </div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
					    <label for="selectAmie" class="col-sm-3 control-label">INSTITUCIÓN EDUCATIVA</label>
					    <div class="col-sm-8">
					     	<span style="font-size: 12px;" class="form-control" id="selectAmie">{{ $amie->amie }} - {{ $amie->distrito }} - {{ $amie->nombre_distrito }} - {{ $amie->institucion }}</span>
					    </div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
					    <label for="frm-buscar" class="col-sm-3 control-label">AMIE</label>
					    <div class="col-sm-2">
			    			<form id="frm-buscar" action="{{ route('buscarAmie') }}" method="POST">
			    			{{ csrf_field() }}
			    				<input class="form-control" type="text" name="cod_amie" value="" onkeyup="this.value = this.value.toUpperCase();" maxlength="10">
			    				<input type="hidden" name="accion" value="mod_ie">
			    				<input type="hidden" name="id_institucion" value="{{ $ie->id }}">
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
	@if(session('user')->id_oferta==22 or session('user')->id_oferta==23  or session('user')->id_oferta==24 or session('user')->id_oferta==25 or session('user')->id_oferta==26  or session('user')->id_oferta==27 or session('user')->id_oferta==28 or session('user')->id_oferta==29 or session('user')->id_oferta==30)
			<form id="frm-guardar" action="{{ route('modificarIE') }}" method="POST">
				{{ csrf_field() }}
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-3 control-label" id="cpl">CPL </label>
							<div class="col-sm-2">
								<select class="form-control" id="id_cpl" name="id_cpl" value="{{ $ie->id_cpl}}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($cpls as $cpl)
								<option class="form-control" style="font-size:10px;" value="{{ $cpl->id }}"
								@if ($cpl->id==$ie->id_cpl)
									selected="selected"
								@endif
								>{{$cpl->id}}-{{$cpl->nombre}}</option>
								@endforeach
							</select>
						</div>
				   </div>

				</div>
			</form>
		@endif


			<div class="panel-footer">
				<div>
					<a href="{{ route('modificarIE') }}" class="btn btn-primary btn-sm"
                        onclick="event.preventDefault(); document.getElementById('frm-modificar').submit();">
                        Guardar cambios
                    </a>
                    <a href="{{ route('institucionesUsuario') }}" class="btn btn-default btn-sm">Cancelar</a>

                    <form id="frm-modificar" action="{{ route('modificarIE') }}" method="POST">
                    {{ csrf_field() }}
                    	<input type="hidden" name="id_institucion" value="{{ $ie->id }}">
                    	<input type="hidden" name="amie" value="{{ $ie->amie }}">
                        <input type="hidden" name="id_cpl" value="{{ $ie->id_cpl }}">

                	</form>
				</div>
			</div>
		</div>
	</div>
@endsection