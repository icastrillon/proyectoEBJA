@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<table class="tbl_encabezado">
		<tr>
			<td style="width: 155px;">
				<img class="img-responsive" src="images/mineduc.png" style="width: 150px; height: 70px;">
			</td>
			<td style="width: 90%;">
				<p class="headTexto">
					SUBSECRETARIA DE EDUCACIÓN ESPECIALIZADA E INCLUSIVA
				</p>
				<p class="headTexto">
					DIRECCIÓN NACIONAL DE EDUCACIÓN PARA PERSONAS CON ESCOLARIDAD INCONCLUSA
				</p>
				<p class="headTexto">
					FORMATO DE REGISTRO DE CALIFICACIONES PARA BÁSICA MEDIA (POST-ALFABETIZACIÓN)
				</p>
			</td>
		</tr>
	</table>

	<table class="table table-condensed table-bordered">
		<tr>
			<td>
				<label style="font-weight: bold; font-size: 12px;">CÓGIGO AMIE:</label>
			</td>
			<td>
				<label>{{ $ie->amie }}</label>
			</td>
			<td>
				<label style="font-weight: bold; font-size: 12px;">INSTITUCIÓN EDUCATIVA:</label>
			</td>
			<td>
				<label>{{ $ie->institucion }}</label>
			</td>
			<td>
				<label style="font-weight: bold; font-size: 12px;">DISTRITO EDUCATIVO:</label> 
			</td>
			<td>
				<label>{{ $ie->distrito }}</label>
			</td>
		</tr>
		<tr>
			<td>
				<label style="font-weight: bold; font-size: 12px;">PERIODO LECTIVO:</label>
			</td>
			<td>
				<label>{{ $oferta->periodo }}</label>
			</td>
			<td>
				<label style="font-weight: bold; font-size: 12px;">OFERTA EDUCATIVA:</label>
			</td>
			<td>
				<label>{{ strtoupper($oferta->nombre) }}</label>
			</td>
			<td>
				<label style="font-weight: bold; font-size: 12px;">AÑO(S) EDUCATIVO(S):</label>
			</td>
			<td>
				@if($oferta->id == 3)
				<label>3 EGB - 4 EGB</label>
				@elseif($oferta->id == 4)
				<label>5 EGB - 6 EGB</label>
				@else
				<label>7 EGB</label>
				@endif
			</td>
		</tr>
		<tr>
			<td>
				<label style="font-weight: bold; font-size: 12px;">ASIGNATURA:</label>
			</td>
			<td>{{ strtoupper($asignatura->nombre) }}</td>
			<td colspan="4">
				<a href="{{ route('calificaciones_post', ['descargar'=>'pdf-post', 'bloque' => $id_materia_oferta, 'id_institucion' => $ie->id]) }}">
					<img class="img-responsive" src="images/pdf.png" style="width: 24px; height: 24px; float: right; margin-right: 20px;" title="Descargar PDF" alt="Descargar PDF">
				</a>
			</td>
		</tr>
	</table>
	<hr style="margin-bottom: 0;">
	<center>
		<label style="font-weight: bold; font-size: 12px;">ESTADOS DE LOS ESTUDIANTES: </label>
		<label style="background-color: #b3ffcc;"><em>P</em> = PROMOVIDO</label>
		<label style="background-color: #ffb3b3;"><em>NP</em> = NO PROMOVIDO</label>
		<label style="background-color: orange;"><em>D</em> = DESERTADO</label>
	</center>
	<hr style="margin-top: 0;">
	<form id="frm_guardar_post" class="form-horizontal" method="POST" action="{{ route('guardarCalifPost') }}">
		{{ csrf_field() }}
		<input type="hidden" name="id_materia_oferta" value="{{ $id_materia_oferta }}">
		<input type="hidden" name="id_institucion" value="{{ $ie->id }}">
		<table class="table table-hover table-condensed table-bordered">
			<thead>
				<th scope="row">No</th>
				<th>ESTUDIANTE</th>
				<th>IDENTIFICACIÓN</th>
				<th>DESERTÓ</th>
				<th>
					<table class="table-condensed table-bordered" width="400">
						<tr>
							<th colspan="4" style="text-align: center;">PROCESO FORMATIVO</th>
						</tr>
						<tr>
							<th class="col-md-1">Parcial 1</th>
							<th class="col-md-1">Parcial 2</th>
							<th class="col-md-1">Promedio General 10/10</th>
							<th class="col-md-1">Promedio Global 80%</th>
						</tr>
					</table>
				</th>
				<th>
					<table class="table-condensed table-bordered" width="200">
						<tr>
							<th colspan="2" style="text-align: center;">PROCESO SUMATIVO</th>
						</tr>
						<tr>
							<th class="col-md-1">Examen 10/10</th>
							<th class="col-md-1">Examen Final 20%</th>
						</tr>
					</table>
				</th>
				<th>EVALUACIÓN COMPORTAMIENTO</th>
				<th>PROMEDIO FINAL</th>
				<th>ESTADO</th>
			</thead>
			<tbody>
				@if (isset($estudiantes))
					@foreach ($estudiantes as $mat)
						<tr>
							<td scope="row">
								{{ $loop->index + 1 }}
								<input type="hidden" name="id_matriculado-{{ $mat->codigo_inscripcion }}" value="{{ $mat->id }}">
							</td>
							<td>{{ $mat->nombres_aspirantes }}</td>
							<td>{{ $mat->cedula_identidad }}</td>
							<td>
								<input type="checkbox" name="ds-{{ $mat->codigo_inscripcion }}" id="ds-{{ $mat->codigo_inscripcion }}" onchange="marcar_desercion('{{ $mat->codigo_inscripcion }}',this);document.getElementById('hds-{{ $mat->codigo_inscripcion }}').value = this.checked;" 
								@if($mat->desertado==true)
									checked="true" 
								@endif>
								<input type="hidden" id="hds-{{ $mat->codigo_inscripcion }}" name="hds-{{ $mat->codigo_inscripcion }}" value="{{ $mat->desertado }}">
							</td>
							<td>
								<table>
									<tr>
										<td>
											<input class="col-md-1 form-control nota-post" type="text" id="p1-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_promedio('{{ $mat->codigo_inscripcion }}');document.getElementById('hp1-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->parcial_1 }}" 
											@if($mat->desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hp1-{{ $mat->codigo_inscripcion }}" id="hp1-{{ $mat->codigo_inscripcion }}" value="{{ $mat->parcial_1 }}">
										</td>
										<td>
											<input class="col-md-1 form-control nota-post" type="text" id="p2-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_promedio('{{ $mat->codigo_inscripcion }}');document.getElementById('hp2-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->parcial_2 }}" 
											@if($mat->desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hp2-{{ $mat->codigo_inscripcion }}" id="hp2-{{ $mat->codigo_inscripcion }}" value="{{ $mat->parcial_2 }}">
										</td>
										<td>
											<input class="col-md-1 form-control nota-post" type="text" id="pm-{{ $mat->codigo_inscripcion }}" maxlength="5" disabled="true" value="{{ $mat->promedio_parciales }}">
											<input type="hidden" name="hpm-{{ $mat->codigo_inscripcion }}" id="hpm-{{ $mat->codigo_inscripcion }}" maxlength="5" value="{{ $mat->promedio_parciales }}">
										</td>
										<td>
											<input class="col-md-1 form-control nota-post" type="text" id="pmg-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" value="{{ bcdiv($mat->promedio_parciales * 0.80,'1',2) }}" disabled="true">
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table>
									<tr>
										<td>
											<input class="col-md-1 form-control nota-post" type="text" id="ex-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_promedio('{{ $mat->codigo_inscripcion }}');document.getElementById('hex-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->examen }}" 
											@if($mat->desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hex-{{ $mat->codigo_inscripcion }}" id="hex-{{ $mat->codigo_inscripcion }}" value="{{ $mat->examen }}">
										</td>
										<td>
											<input class="col-md-1 form-control nota-post" type="text" id="exs-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" value="{{ bcdiv($mat->examen * 0.20,'1',2) }}" disabled="true">
										</td>
									</tr>
								</table>
							</td>
							<td>
								<select style="font-size: 11px; height: 25px; width: 120px;" class="form-control" id="evcomp-{{ $mat->codigo_inscripcion }}" value="{{ $mat->comportamiento }}" onchange="calcular_promedio('{{ $mat->codigo_inscripcion }}');document.getElementById('hevcomp-{{ $mat->codigo_inscripcion }}').value = this.value;" 
									@if($mat->desertado==true)
										disabled="true" 
									@endif>
									<option value="-1">seleccionar</option>
									<option value="A" 
									@if($mat->comportamiento=='A') 
										selected="selected" 
									@endif>Muy Satisfactorio</option>
									<option value="B" 
									@if($mat->comportamiento=='B') 
										selected="selected" 
									@endif>Satisfactorio</option>
									<option value="C" 
									@if($mat->comportamiento=='C') 
										selected="selected" 
									@endif>Poco Satisfactorio</option>
									<option value="D" 
									@if($mat->comportamiento=='D') 
										selected="selected" 
									@endif>Mejorable</option>
									<option value="E" 
									@if($mat->comportamiento=='E') 
										selected="selected" 
									@endif>Insatisfactorio</option>
								</select>
								<input type="hidden" name="hevcomp-{{ $mat->codigo_inscripcion }}" id="hevcomp-{{ $mat->codigo_inscripcion }}" value="{{ $mat->comportamiento }}">
							</td>
							<td>
								<input class="col-md-1 form-control nota nota_fin" type="text" id="nf-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" value="{{ $mat->nota_final }}" disabled="true">
								<input type="hidden" name="hnf-{{ $mat->codigo_inscripcion }}" id="hnf-{{ $mat->codigo_inscripcion }}" value="{{ $mat->nota_final }}">
							</td>
							<td>
								<input class="col-md-1 form-control nota" type="text" id="em-{{ $mat->codigo_inscripcion }}" value="{{ $mat->estado }}" disabled="true" 
								@if($mat->estado=='D') 
									style="font-weight: bold;background-color:orange;" 
								@elseif($mat->estado=='NP')
									style="font-weight: bold;background-color:#ffb3b3;"
								@elseif($mat->estado=='P')
									style="font-weight: bold;background-color:#b3ffcc;"
								@else
									style="font-weight: bold;background-color:transparent;"
								@endif>
								<input type="hidden" name="hem-{{ $mat->codigo_inscripcion }}" id="hem-{{ $mat->codigo_inscripcion }}" value="{{ $mat->estado }}">
							</td>
						</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	</form>
	@if (isset($estudiantes) and $estudiantes->count()>0)
	<button type="button" class="btn btn-primary" onclick="guardarPost(event);">Guardar Calificaciones</button>
	@endif
	<a class="btn btn-default" href="{{ route('calificaciones_prepost', ['token' => session('token')]) }}">Regresar</a>
</div>
@endsection