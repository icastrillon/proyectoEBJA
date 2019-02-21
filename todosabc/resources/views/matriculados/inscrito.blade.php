@extends('layouts.app')

@section('content')
<div class="container">
	@if (session('estadoMat')==500)
	<div class="alert alert-danger alert-dismissible" role="alert">
		{{ session('msgMat') }}
	</div>
	@endif
	<div class="panel panel-primary">
		<div class="panel-heading">Paso 3: Matriculados > Inscrito Seleccionado</div>
		<div class="panel-body">
			<form id="frm-matricular" action="{{ route('matricularInscrito') }}" method="POST">
				{{ csrf_field() }}
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="identificacion">IDENTIFICACIÓN</label>
						<div class="col-sm-5">
							<span class="form-control lbl" id="identificacion">{{ $inscrito->cedula_identidad }}</span>
							<input type="hidden" name="cedula_identidad" value="{{ $inscrito->cedula_identidad }}">
							<input type="hidden" name="codigo_inscripcion" value="{{ $inscrito->codigo_inscripcion }}">
							<input type="hidden" name="id_inscrito" value="{{ $inscrito->id }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="tipo_documento">TIPO DE IDENTIFICACIÓN</label>
						<div class="col-sm-5">
							<span class="form-control lbl" id="tipo_documento">{{ $inscrito->tipo_documento_identidad }}</span>
							<input type="hidden" name="tipo_documento_identidad" value="{{ $inscrito->tipo_documento_identidad }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group ">
						<label class="col-sm-4 control-label" for="nombres">* NOMBRES</label>
						<div class="col-sm-5">
							@if ($inscrito->tipo_documento_identidad!='CEDULA')
								<input class="form-control" type="text" id="nombres" name="nombres_aspirantes" value="{{ $inscrito->nombres_aspirantes }}" placeholder="NOMBRES" maxlength="200" onkeyup="this.value = this.value.toUpperCase();" onkeypress="return (event.charCode >= 65 && event.charCode <= 122 || event.charCode ===241 || event.charCode ===209)">
							@else
								<span class="form-control lbl" id="nacimiento">{{ $inscrito->nombres_aspirantes }}</span>
								<input type="hidden" name="nombres_aspirantes" value="{{ $inscrito->nombres_aspirantes }}">
							@endif
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="nacimiento">* FECHA NACIMIENTO</label>
						<div class="col-sm-5">
							@if ($inscrito->tipo_documento_identidad!='CEDULA')
								<input class="form-control" type="text" id="nacimiento" name="fecha_nacimiento" value="{{ $inscrito->fecha_nacimiento }}" placeholder="Ej: 1980-11-30" maxlength="10">
							@else
								<span class="form-control lbl" id="nacimiento">{{ $inscrito->fecha_nacimiento }}</span>
								<input type="hidden" name="fecha_nacimiento" value="{{ $inscrito->fecha_nacimiento }}">
							@endif
							<input type="hidden" name="fecha_inscripcion" value="{{ $inscrito->fecha_inscripcion }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="genero">* GENERO</label>
						<div class="col-sm-5">
							@if ($inscrito->tipo_documento_identidad!='CEDULA')
								<select class="form-control" id="genero" name="genero" value="{{ $inscrito->genero }}">
								@foreach ($generos as $val)
									<option value="{{ $val->nombre }}"
										@if ($val->nombre == $inscrito->genero)
										selected="selected"
										@endif
										>{{ $val->nombre }}</option>
								@endforeach
								</select>
							@else
								<span class="form-control lbl" id="genero">{{ $inscrito->genero }}</span>
								<input type="hidden" name="genero" value="{{ $inscrito->genero }}">
							@endif
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="estado_civil">* ESTADO CIVIL</label>
						<div class="col-sm-5">
							@if ($inscrito->tipo_documento_identidad!='CEDULA')
								<select class="form-control" id="estado_civil" name="estado_civil" value="{{ $inscrito->estado_civil }}">
									<option value="-1">--Seleccionar--</option>
									@foreach ($estados_civiles as $val)
									<option value="{{ $val->estado_civil }}"
										@if ($val->estado_civil == $inscrito->estado_civil)
										selected="selected"
										@endif
										>{{ $val->estado_civil }}</option>
									@endforeach
								</select>
							@else
								<span class="form-control lbl" id="estado_civil">{{ $inscrito->estado_civil }}</span>
								<input type="hidden" name="estado_civil" value="{{ $inscrito->estado_civil }}">
							@endif
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="etnia">* ETNIA</label>
						<div class="col-sm-5">
							<select class="form-control" id="etnia" name="etnia" value="{{ $inscrito->etnia }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($etnias as $val)
								<option value="{{ $val->etnia }}"
									@if ($val->etnia == $inscrito->etnia)
									selected="selected"
									@endif
									>{{ $val->etnia }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="situacion_laboral">* SITUACIÓN LABORAL</label>
						<div class="col-sm-5">
							<select class="form-control" id="situacion_laboral" name="situacion_laboral" value="{{ $inscrito->situacion_laboral }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($situaciones_laborales as $val)
								<option value="{{ $val->situacion_laboral }}"
									@if ($val->situacion_laboral == $inscrito->situacion_laboral)
									selected="selected"
									@endif
									>{{ $val->situacion_laboral }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="actividad_economica">* ACTIVIDAD ECONÓMICA</label>
						<div class="col-sm-5">
							<select class="form-control" id="actividad_economica" name="actividad_economica" value="{{ $inscrito->actividad_economica }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($actividades_economicas as $val)
								<option value="{{ $val->nombre }}"
									@if ($val->nombre == $inscrito->actividad_economica)
									selected="selected"
									@endif
									>{{ $val->nombre }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="datos_familiares">* DATOS FAMILIARES</label>
						<div class="col-sm-5">
							<select class="form-control" id="datos_familiares" name="datos_familiares" value="{{ $inscrito->datos_familiares }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($datos_familiares as $val)
								<option value="{{ $val->datos_familiares }}"
									@if ($val->datos_familiares == $inscrito->datos_familiares)
									selected="selected"
									@endif
									>{{ $val->datos_familiares }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="nacionalidad">* NACIONALIDAD</label>
						<div class="col-sm-5">
							@if ($inscrito->tipo_documento_identidad!='CEDULA')
								<select class="form-control" id="nacionalidad" name="nacionalidad" value="{{ $inscrito->nacionalidad }}">
									<option value="-1">--Seleccionar--</option>
									@foreach ($nacionalidades as $val)
									<option value="{{ $val->nacionalidad }}"
										@if ($val->nacionalidad == $inscrito->nacionalidad)
										selected="selected"
										@endif
										>{{ $val->nacionalidad }}</option>
									@endforeach
								</select>
							@else
								<span class="form-control lbl" id="nacionalidad">{{ $inscrito->nacionalidad }}</span>
								<input type="hidden" name="nacionalidad" value="{{ $inscrito->nacionalidad }}">
							@endif
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="telefono_celular">TELÉFONO CELULAR</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="telefono_celular" name="telefono_celular" value="{{ $inscrito->telefono_celular }}" maxlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="email">EMAIL</label>
						<div class="col-sm-5">
							<input class="form-control" type="email" id="email" name="email" value="{{ $inscrito->email }}" maxlength="200">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="rezago_educativo">* REZAGO EDUCATIVO</label>
						<div class="col-sm-5">
							<select class="form-control" id="rezago_educativo" name="rezago_educativo" value="{{ $inscrito->rezago_educativo }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($rezagos_educativos as $val)
								<option value="{{ $val->rezago_educativo }}"
									@if ($val->rezago_educativo == $inscrito->rezago_educativo)
									selected="selected"
									@endif
									>{{ $val->rezago_educativo }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="ultimo_anio_aprobado">* ÚLTIMO AÑO APROBADO</label>
						<div class="col-sm-3">
							<select class="form-control" id="ultimo_anio_aprobado" name="ultimo_anio_aprobado"
							value="{{ $inscrito->ultimo_anio_aprobado }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($ultimos_anios_aprobados as $val)
								<option value="{{ $val->ultimo_anio_aprobado }}"
									@if ($val->ultimo_anio_aprobado == $inscrito->ultimo_anio_aprobado)
									selected="selected"
									@endif
									>{{ $val->ultimo_anio_aprobado }}</option>
								@endforeach
							</select>
						</div>

 					<label for="anuncio  font-size: 14px" > *Valide con la documentación del estudiante</label>
					</div>
				</div>

				<div class="form-horizontal">
					@if (session('user')->id_oferta==8 or session('user')->id_oferta==15 )
					<div class="form-group">
						<label class="col-sm-4 control-label" for="oferta_educativa">* OFERTA EDUCATIVA</label>
						<div class="col-sm-5">
							<select class="form-control" id="oferta_educativa" name="oferta_educativa" value="{{ $inscrito->oferta_educativa }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($ofertas_educativas as $val)
								<option value="{{ $val->id }}">{{ $val->nombre }}</option>
								@endforeach
							</select>
						</div>
					</div>
				@else
					<input type="hidden" name="oferta_educativa" value="{{ session('user')->id_oferta }}">
					@endif
				</div>

				<div class="form-horizontal">
					@if (session('user')->id_oferta==2 or session('user')->id_oferta==10  )
					<div class="form-group">
						<label class="col-sm-4 control-label" for="id_docente">DOCENTE-INSTITUCION</label>
						<div class="col-sm-6">
							@if (isset($docentes) and $docentes->count() > 0)
								@foreach ($docentes as $doc)
									<span class="form-control lbl" style="font-size:10px;">{{ $doc->apellidos }} {{ $doc->nombres }} : {{ $doc->amie }} - {{ $doc->institucion }}</span>
								@endforeach
							@endif
							<input type="hidden" name="id_docente" value="{{ $id_docente }}">
						</div>
					</div>
					@elseif (session('user')->id_oferta==8 or session('user')->id_oferta==15  or session('user')->id_oferta==16 or session('user')->id_oferta==17 or session('user')->id_oferta==19 or session('user')->id_oferta==22 or session('user')->id_oferta==23 or session ('user')->id_oferta==24 )
					<div class="form-group">
						<label class="col-sm-4 control-label" for="id_institucion">* INSTITUCIÓN</label>
						<div class="col-sm-5">
							<select class="form-control" id="id_institucion" name="id_institucion" value="{{ $id_institucion }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($ies as $ie)
								<option class="form-control" style="font-size:10px;" value="{{ $ie->id }}">{{ $ie->institucion }}</option>
								@endforeach
							</select>
							<input type="hidden" name="id_docente" value="{{ $id_docente }}">
						</div>
					</div>
					@else
					<div class="form-group">
						<label class="col-sm-4 control-label" for="id_institucion">INSTITUCIÓN</label>
						<div class="col-sm-6">
							<span class="form-control lbl" style="font-size:10px;">{{ $ies[0]->amie }} - {{ $ies[0]->institucion }}</span>
							<input type="hidden" name="id_institucion" value="{{ $ies[0]->id }}">
						</div>
					</div>
					@endif
				</div>

				@if (session('user')->id_oferta!=2 and session('user')->id_oferta!=10)
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="paralelo">* PARALELO</label>
						<div class="col-sm-5">
							<select class="form-control" id="paralelo" name="paralelo" value="{{ $paralelo }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($paralelos as $val)
								<option value="{{ $val }}"
									@if ($val == $paralelo)
									selected="selected"
									@endif
									>{{ $val }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				@endif

				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="nom_zona">* ZONA</label>
						<div class="col-sm-5">
							<select class="form-control" id="nom_zona" name="nom_zona" value="{{ $inscrito->nom_zona }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($zonas as $val)
								<option value="{{ $val->nombre }}"
									@if ($val->nombre == $inscrito->nom_zona)
									selected="selected"
									@endif
									>{{ $val->nombre }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="nom_provincia">* PROVINCIA</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="nom_provincia" name="nom_provincia" value="{{ $inscrito->nom_provincia }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="nom_canton">* CANTÓN</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="nom_canton" name="nom_canton" value="{{ $inscrito->nom_canton }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="nom_parroquia">* PARROQUIA</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="nom_parroquia" name="nom_parroquia" value="{{ $inscrito->nom_parroquia }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="direccion">* DIRECCION</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="direccion" name="direccion" value="{{ $inscrito->direccion }}">
						</div>
					</div>
				</div>
			@if (session('user')->id_oferta==8 or session('user')->id_oferta==15 or session('user')->id_oferta==19 or session('user')->id_oferta==16 or session('user')->id_oferta==17 or session('user')->id_oferta==22 or session('user')->id_oferta==23 or session ('user')->id_oferta==24 )
				<div class="form-horizontal">
					<div class="form-group">
					    <label for="lugar" class="col-sm-4 control-label">* Estudiante atendido en:</label>
					    <div class="col-sm-5">
						    <select class="form-control" id="lugar" name="lugar" value="{{ $lugar }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($lugares_atencion as $val)
								<option value="{{ $val }}"
									@if ($val == $lugar)
									selected="selected"
									@endif
									>{{ $val }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				@endif

				<div class="form-horizontal">
				    <label for="asistencia" class="col-sm-4 control-label">* ASISTENCIA</label>
				    <div class="col-sm-5 alert alert-warning" role="alert">
				    	¿El participante asiste a clases?
				        <div class="form-check form-check-inline">
						  <label class="form-check-label">
						    <input class="form-check-input" type="checkbox" name="si_asiste_con_frecuencia" value="SI"> Asiste con frecuencia
						  </label>
						</div>
						<div class="form-check form-check-inline">
						  <label class="form-check-label">
						    <input class="form-check-input" type="checkbox" name="no_asiste_con_frecuencia" value="NO"> Desertó
						  </label>
						</div>
				    </div>
				</div>
			</form>
		</div>
		<div class="panel-footer">
			<div>
				<a href="{{ route('matricularInscrito') }}" class="btn btn-success btn-sm"
                    onclick="event.preventDefault(); document.getElementById('frm-matricular').submit();">
                    Matricular
                </a>
                <a href="{{ route('nuevoMatriculado') }}" class="btn btn-default btn-sm">Cancelar</a>
			</div>
		</div>
	</div>
</div>
@endsection