@extends('layouts.app')

@section('content')
	<div class="container">
		@if (session('estadoDoc')==500)
		<div class="alert alert-danger" role="alert">
			{{ session('msgDoc') }}
		</div>
		@endif
		<div class="panel panel-primary">
			<div class="panel-heading">Paso 2: Docentes > Nuevo Docente</div>
			<div class="panel-body">
				<form id="frm-guardar" action="{{ route('guardarDoc') }}" method="POST">                    
                    {{ csrf_field() }}
					<div class="form-horizontal">
						<div class="form-group">
						    <label for="cedula" class="col-sm-3 control-label">* IDENTIFICACIÓN</label>
						    <div class="col-sm-7">
						      @if (session('user')->id_oferta < 6)
						      <span class="form-control lbl" id="cedula">{{ $docente->cedula }}</span>
						      @else
						      <input type="text" name="cedula" value="{{ $docente->cedula }}" class="form-control" id="cedula" maxlength="15" placeholder="CEDULA o PASAPORTE" onkeyup="this.value = this.value.toUpperCase();">
						      @endif
						    </div>
						</div>
					</div>
					<div class="form-horizontal">
						<div class="form-group">
						    <label for="apellidos" class="col-sm-3 control-label">* APELLIDOS</label>
						    <div class="col-sm-7">
						      @if (session('user')->id_oferta < 6)
						      <span class="form-control lbl" id="apellidos">{{ strtoupper($docente->apellidos) }}</span>
						      @else
						      <input type="text" name="apellidos" value="{{ $docente->apellidos }}" class="form-control" id="apellidos" maxlength="60" placeholder="APELLIDOS" onkeyup="this.value = this.value.toUpperCase();" onkeypress="return (event.charCode >= 65 && event.charCode <= 122 || event.charCode ===241 || event.charCode ===209 || event.charCode === 32 || event.charCode === 0)">
						      @endif
						    </div>
						</div>
					</div>
					<div class="form-horizontal">
						<div class="form-group">
						    <label for="nombres" class="col-sm-3 control-label">* NOMBRES</label>
						    <div class="col-sm-7">
						      @if (session('user')->id_oferta < 6)
						      <span class="form-control lbl" id="nombres">{{ strtoupper($docente->nombres) }}</span>
						      @else
						      <input type="text" name="nombres" value="{{ $docente->nombres }}" class="form-control" id="nombres" maxlength="60" placeholder="NOMBRES" onkeyup="this.value = this.value.toUpperCase();" onkeypress="return (event.charCode >= 65 && event.charCode <= 122 || event.charCode ===241 || event.charCode ===209 || event.charCode === 32 || event.charCode === 0)">
						      @endif
						    </div>
						</div>
					</div>
					<div class="form-horizontal">
						<div class="form-group">
						    <label for="email" class="col-sm-3 control-label">* EMAIL</label>
						    <div class="col-sm-7">
						      <input class="form-control" type="email" id="email" name="email" value="{{ $docente->email }}" placeholder="EMAIL" maxlength="220">
						    </div>
						</div>
					</div>
					<div class="form-horizontal">
						<div class="form-group">
						    <label for="telefono" class="col-sm-3 control-label">* TELÉFONO</label>
						    <div class="col-sm-7">
						      <input class="form-control" id="telefono" name="telefono" value="{{ $docente->telefono }}" maxlength="10" placeholder="TELÉFONO" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
						    </div>
						</div>
					</div>
					<div class="form-horizontal">
						<div class="form-group">
						    <label for="institucion" class="col-sm-3 control-label">INSTITUCIÓN</label>
						    <select class="form-control" name="id_institucion" style="width: 450px; font-size: 10px;">
					    		@foreach($ies as $i)
					    		<option value="{{ $i->id }}">{{ $i->zona }} - {{ $i->amie }} - {{ $i->institucion }}</option>
					    		@endforeach
					    	</select>
						</div>
					</div>
					@if (session('user')->id_oferta==2)
					<div class="form-horizontal">
					    <label for="tiene_voluntario" class="col-sm-3 control-label"><span style="color: red; font-weight: bold; font-size: 20px;">--></span> TIENE VOLUNTARIO</label>
					    <div class="col-sm-7 alert alert-warning" role="alert">
					    	¿El Docente cuenta con un Voluntario, el mismo que le colabora en el proceso en Aula?
					        <div class="form-check form-check-inline">
							  <label class="form-check-label">
							    <input class="form-check-input" type="checkbox" name="si_tiene_voluntario" value="SI"> SI
							  </label>
							</div>
							<div class="form-check form-check-inline">
							  <label class="form-check-label">
							    <input class="form-check-input" type="checkbox" name="no_tiene_voluntario" value="NO"> NO
							  </label>
							</div>
					    </div>
					</div>
					@elseif(session('user')->id_oferta==10 )
					<div class="form-horizontal">
					    <label for="clasificacion" class="col-sm-3 control-label"><span style="color: red; font-weight: bold; font-size: 20px;">--></span> CLASIFICACIÓN:</label>
					    <div class="col-sm-7 alert alert-warning" role="alert">
					    	¿El Docente en qué clasificación se encuentra?
					        <div class="form-check form-check-inline">
							  	<select class="form-control" id="clasificacion" name="clasificacion" style="width: 450px; font-size: 10px;" value="{{ $docente->clasificacion }}">
					    			<option value="Docente Nombramiento con Horas extra">Docente Nombramiento con Horas extra</option> 
									<option value="Docente Ed. Básica Media - Post con Horas extra">Docente Ed. Básica Media - Post con Horas extra</option>
									<option value="Docente Ed. Básica Media - Post sin Horas extra">Docente Ed. Básica Media - Post sin Horas extra</option>
									<option value="Docente Básica Superior Intensiva con Horas extra">Docente Básica Superior Intensiva con Horas extra</option>
									<option value="Docente Bachillerato Intensivo con Horas extra">Docente Bachillerato Intensivo con Horas extra</option>
					    		</select>
							</div>
					    </div>
					</div>

					@elseif(session('user')->id_oferta==16  or session('user')->id_oferta==17 )
					<div class="form-horizontal">
					    <label for="clasificacion" class="col-sm-3 control-label"><span style="color: red; font-weight: bold; font-size: 20px;">--></span> CLASIFICACIÓN:</label>
					    <div class="col-sm-7 alert alert-warning" role="alert">
					    	¿El Docente en qué clasificación se encuentra?
					        <div class="form-check form-check-inline">
							  	<select class="form-control" id="clasificacion" name="clasificacion" style="width: 450px; font-size: 10px;" value="{{ $docente->clasificacion }}">
					    			<option value="Docente Post Alfabetización">Docente Post Alfabetización</option>
									<option value="Docente Básica Superior">Docente Básica Superior Intensiva </option>
									<option value="Docente Bachillerato Intensivo">Docente Bachillerato Intensivo</option>
					    		</select>
							</div>
					    </div>
					</div>


					@endif
				</form>
			</div>
			<div class="panel-footer">
				<a href="{{ route('guardarDoc') }}" class="btn btn-primary btn-sm" 
                    onclick="event.preventDefault(); document.getElementById('frm-guardar').submit();">
                    Guardar Docente
                </a>
                <a href="{{ route('docentesUsuario') }}" class="btn btn-default btn-sm">Cancelar</a>
			</div>					
		</div>
	</div>
@endsection