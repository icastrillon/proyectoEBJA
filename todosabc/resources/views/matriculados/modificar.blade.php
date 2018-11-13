@extends('layouts.app')

@section('content')
<div class="container">
	@if (session('estadoMat')==500)
	<div class="alert alert-danger alert-dismissible" role="alert">
		{{ session('msgMat') }}
	</div>
	@endif
	<div class="panel panel-primary">
		<div class="panel-heading">Paso 3: Matriculados > Matriculado Seleccionado</div>
		<div class="panel-body">
			<form id="frm-matricular" action="{{ route('modificarMat') }}" method="POST">
				{{ csrf_field() }}
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="identificacion">IDENTIFICACIÓN</label>
						<div class="col-sm-5">
							<span class="form-control lbl" id="identificacion">{{ $matriculado->cedula_identidad }}</span>
							<input type="hidden" name="cedula_identidad" value="{{ $matriculado->cedula_identidad }}">
							<input type="hidden" name="codigo_inscripcion" value="{{ $matriculado->codigo_inscripcion }}">
							<input type="hidden" name="id_matriculado" value="{{ $matriculado->id }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="tipo_documento">TIPO DE IDENTIFICACIÓN</label>
						<div class="col-sm-5">
							<span class="form-control lbl" id="tipo_documento">{{ $matriculado->tipo_documento_identidad }}</span>
							<input type="hidden" name="tipo_documento_identidad" value="{{ $matriculado->tipo_documento_identidad }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group ">
						<label class="col-sm-4 control-label" for="nombres">* NOMBRES</label>
						<div class="col-sm-5">
							@if ($matriculado->tipo_documento_identidad!='CEDULA')
								<input class="form-control" type="text" id="nombres" name="nombres_aspirantes" value="{{ $matriculado->nombres_aspirantes }}">
							@else
								<span class="form-control lbl" id="nacimiento">{{ $matriculado->nombres_aspirantes }}</span>
								<input type="hidden" name="nombres_aspirantes" value="{{ $matriculado->nombres_aspirantes }}">
							@endif
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="nacimiento">* FECHA NACIMIENTO</label>
						<div class="col-sm-5">
							@if ($matriculado->tipo_documento_identidad!='CEDULA')
								<input class="form-control" type="text" id="nacimiento" name="fecha_nacimiento" value="{{ $matriculado->fecha_nacimiento }}" placeholder="Ej: 1980-11-30" maxlength="10">
							@else
								<span class="form-control lbl" id="nacimiento">{{ $matriculado->fecha_nacimiento }}</span>
								<input type="hidden" name="fecha_nacimiento" value="{{ $matriculado->fecha_nacimiento }}">
							@endif
							<input type="hidden" name="fecha_inscripcion" value="{{ $matriculado->fecha_inscripcion }}">						
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="genero">* GENERO</label>
						<div class="col-sm-5">
							@if ($matriculado->tipo_documento_identidad!='CEDULA')
								<select class="form-control" id="genero" name="genero" value="{{ $matriculado->genero }}">
								@foreach ($generos as $val)
									<option value="{{ $val->nombre }}" 
										@if ($val->nombre == $matriculado->genero)
										selected="selected" 
										@endif
										>{{ $val->nombre }}</option>
								@endforeach
								</select>
							@else
								<span class="form-control lbl" id="genero">{{ $matriculado->genero }}</span>
								<input type="hidden" name="genero" value="{{ $matriculado->genero }}">
							@endif
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="estado_civil">* ESTADO CIVIL</label>
						<div class="col-sm-5">
							@if ($matriculado->tipo_documento_identidad!='CEDULA')
								<select class="form-control" id="estado_civil" name="estado_civil" value="{{ $matriculado->estado_civil }}">
									<option value="-1">--Seleccionar--</option>
									@foreach ($estados_civiles as $val)
									<option value="{{ $val->estado_civil }}" 
										@if ($val->estado_civil == $matriculado->estado_civil)
										selected="selected" 
										@endif
										>{{ $val->estado_civil }}</option>
									@endforeach
								</select>
							@else
								<span class="form-control lbl" id="estado_civil">{{ $matriculado->estado_civil }}</span>
								<input type="hidden" name="estado_civil" value="{{ $matriculado->estado_civil }}">
							@endif
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="etnia">* ETNIA</label>
						<div class="col-sm-5">
							<select class="form-control" id="etnia" name="etnia" value="{{ $matriculado->etnia }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($etnias as $val)
								<option value="{{ $val->etnia }}" 
									@if ($val->etnia == $matriculado->etnia)
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
							<select class="form-control" id="situacion_laboral" name="situacion_laboral" value="{{ $matriculado->situacion_laboral }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($situaciones_laborales as $val)
								<option value="{{ $val->situacion_laboral }}" 
									@if ($val->situacion_laboral == $matriculado->situacion_laboral)
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
							<select class="form-control" id="actividad_economica" name="actividad_economica" value="{{ $matriculado->actividad_economica }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($actividades_economicas as $val)
								<option value="{{ $val->nombre }}" 
									@if ($val->nombre == $matriculado->actividad_economica)
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
							<select class="form-control" id="datos_familiares" name="datos_familiares" value="{{ $matriculado->datos_familiares }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($datos_familiares as $val)
								<option value="{{ $val->datos_familiares }}" 
									@if ($val->datos_familiares == $matriculado->datos_familiares)
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
							@if ($matriculado->tipo_documento_identidad!='CEDULA')
								<select class="form-control" id="nacionalidad" name="nacionalidad" value="{{ $matriculado->nacionalidad }}">
									<option value="-1">--Seleccionar--</option>
									@foreach ($nacionalidades as $val)
									<option value="{{ $val->nacionalidad }}" 
										@if ($val->nacionalidad == $matriculado->nacionalidad)
										selected="selected" 
										@endif
										>{{ $val->nacionalidad }}</option>
									@endforeach
								</select>
							@else
								<span class="form-control lbl" id="nacionalidad">{{ $matriculado->nacionalidad }}</span>
								<input type="hidden" name="nacionalidad" value="{{ $matriculado->nacionalidad }}">
							@endif
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="telefono_celular">TELÉFONO CELULAR</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="telefono_celular" name="telefono_celular" value="{{ $matriculado->telefono_celular }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="email">EMAIL</label>
						<div class="col-sm-5">
							<input class="form-control" type="email" id="email" name="email" value="{{ $matriculado->email }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="rezago_educativo">* REZAGO EDUCATIVO</label>
						<div class="col-sm-5">
							<select class="form-control" id="rezago_educativo" name="rezago_educativo" value="{{ $matriculado->rezago_educativo }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($rezagos_educativos as $val)
								<option value="{{ $val->rezago_educativo }}" 
									@if ($val->rezago_educativo == $matriculado->rezago_educativo)
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
						<div class="col-sm-5">
							<select class="form-control" id="ultimo_anio_aprobado" name="ultimo_anio_aprobado" value="{{ $matriculado->ultimo_anio_aprobado }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($ultimos_anios_aprobados as $val)
								<option value="{{ $val->ultimo_anio_aprobado }}" 
									@if ($val->ultimo_anio_aprobado == $matriculado->ultimo_anio_aprobado)
									selected="selected" 
									@endif
									>{{ $val->ultimo_anio_aprobado }}</option>
								@endforeach
							</select>
						</div>
						<button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="
ULTIMO AÑO APROBADO - 》CURSA
Ninguno -》 2-3EGB
1 EGB -》 2-3 EGB
2 EGB - 》 3EGB
3 EGB - 》 4-5 EGB ABC3
4 EGB - 》 5-6 EGB
5 EGB - 》 5-6 EGB
6 EGB - 》7 EGB
7 EGB - 》 8-9-10 EGB
8 EGB - 》 8-9-10 EGB
9 EGB - 》 8-9-10 EGB
10 EGB -》1-2-3 BGU
1 BGU - 》 2-3 BGU
2 BGU -》 3 BGU">i</button>
					</div>
				</div>
				
				<div class="form-horizontal">
					@if (session('user')->id_oferta==8 or session('user')->id_oferta==15)
					<div class="form-group">
						<label class="col-sm-4 control-label" for="oferta_educativa">* OFERTA EDUCATIVA</label>
						<div class="col-sm-5">
							<select class="form-control" id="oferta_educativa" name="oferta_educativa" value="{{ $matriculado->id_oferta }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($ofertas_educativas as $val)
								<option value="{{ $val->id }}" 
									@if ($val->id == $matriculado->id_oferta)
									selected="selected" 
									@endif
									>{{ $val->nombre }}</option>
								@endforeach
							</select>
						</div>
					</div>
					@else
					<input type="hidden" name="oferta_educativa" value="{{ session('user')->id_oferta }}">
					@endif
				</div>
				
				<div class="form-horizontal">
					@if (session('user')->id_oferta==2 or session('user')->id_oferta==10)
					<div class="form-group">
						<label class="col-sm-4 control-label" for="id_docente">DOCENTE-INSTITUCION</label>
						<div class="col-sm-6">
							@if (isset($docentes) and $docentes->count() > 0)
								@foreach ($docentes as $doc)
									<span class="form-control lbl" style="font-size:10px;">{{ $doc->apellidos }} {{ $doc->nombres }} : {{ $doc->amie }} - {{ $doc->institucion }}</spann>
								@endforeach
							@endif
						</div>
					</div>
					@elseif (session('user')->id_oferta==8 or session('user')->id_oferta==15)
					<div class="form-group">
						<label class="col-sm-4 control-label" for="id_institucion">* INSTITUCIÓN</label>
						<div class="col-sm-5">
							<select class="form-control" id="id_institucion" name="id_institucion" value="{{ $id_institucion }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($ies as $ie)
								<option class="form-control" style="font-size:10px;" value="{{ $ie->id }}"
								@if ($ie->id==$id_institucion)
									selected="selected"
								@endif
								>{{ $ie->institucion }}</option>
								@endforeach
							</select>
							<input type="hidden" name="id_docente" value="{{ $id_docente }}">
						</div>
					</div>
					@else
					<div class="form-group">
						<label class="col-sm-4 control-label" for="id_docente">INSTITUCIÓN</label>
						<div class="col-sm-6">
							<span class="form-control lbl" style="font-size:10px;">{{ $ies[0]->amie }} - {{ $ies[0]->institucion }}</span>
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
							<select class="form-control" id="nom_zona" name="nom_zona" value="{{ $matriculado->nom_zona }}">
								<option value="-1">--Seleccionar--</option>
								@foreach ($zonas as $val)
								<option value="{{ $val->nombre }}" 
									@if ($val->nombre == $matriculado->nom_zona)
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
							<input class="form-control" type="text" id="nom_provincia" name="nom_provincia" value="{{ $matriculado->nom_provincia }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="nom_canton">* CANTÓN</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="nom_canton" name="nom_canton" value="{{ $matriculado->nom_canton }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="nom_parroquia">* PARROQUIA</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="nom_parroquia" name="nom_parroquia" value="{{ $matriculado->nom_parroquia }}">
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="direccion">* DIRECCION</label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="direccion" name="direccion" value="{{ $matriculado->direccion }}">
						</div>
					</div>
				</div>
				@if (session('user')->id_oferta==8 or session('user')->id_oferta==15)
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
				    <label for="asistencia" class="col-sm-4 control-label">ASISTENCIA</label>
				    <div class="col-sm-5 alert alert-warning" role="alert">
				    	¿El participante asiste con frecuencia a clases?
				        <div class="form-check form-check-inline">
						  <label class="form-check-label">
						    <input class="form-check-input" type="checkbox" name="si_asiste_con_frecuencia" value="SI" @if ($matriculado->asiste_con_frecuencia===true) checked="checked" @endif> Asiste con frecuencia
						  </label>
						</div>
						<div class="form-check form-check-inline">
						  <label class="form-check-label">
						    <input class="form-check-input" type="checkbox" name="no_asiste_con_frecuencia" value="NO" @if ($matriculado->asiste_con_frecuencia===false) checked="checked" @endif> Desertó
						  </label>
						</div>
				    </div>
				</div>
			</form>
		</div>
		<div class="panel-footer">
			<div>
				<a href="{{ route('modificarMat') }}" class="btn btn-success btn-sm" 
                    onclick="event.preventDefault(); document.getElementById('frm-matricular').submit();">
                    Guardar cambios
                </a>
                <a href="{{ route('matriculadosUsuario') }}" class="btn btn-default btn-sm">Cancelar</a>
			</div>
		</div>
	</div>
</div>
@endsection