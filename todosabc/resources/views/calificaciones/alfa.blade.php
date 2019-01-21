@extends('layouts.app')

@section('content')
<div class="container-fluid">
	@if(session('estadoK')==400)
		<div class="alert alert-danger alert-dismissible" role="alert">
			Falta completar el registro de calificaciones
			@component('componentes/limpiar_mensaje_calificaciones') 

			@endcomponent
		</div>
	@endif
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
					FORMATO DE REGISTRO DE CALIFICACIONES PARA ALFABETIZACIÓN
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
			<td></td>
		</tr>
		<tr>
			<td>
				<label style="font-weight: bold; font-size: 12px;">PERIODO LECTIVO:</label>
			</td>
			<td>
				<label>{{ session('oferta')->periodo }}</label>
			</td>
			<td>
				<label style="font-weight: bold; font-size: 12px;">OFERTA EDUCATIVA:</label>
			</td>
			<td>
				<label>ALFABETIZACIÓN</label>
			</td>
			<td>
				<label style="font-weight: bold; font-size: 12px;">AÑO(S) EDUCATIVO(S):</label>
			</td>
			<td>
				<label>2 EGB</label>
			</td>
			<td>
				<a href="{{ route('calificaciones_alfa', ['descargar'=>'pdf-alfa']) }}">
					<img class="img-responsive" src="images/pdf.png" style="width: 24px; height: 24px;" title="Descargar PDF" alt="Descargar PDF">
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
	<form id="frm_guardar_alfa" class="form-horizontal" method="POST" action="{{ route('guardarCalifAlfa') }}">
		{{ csrf_field() }}
		<table class="table table-hover table-condensed table-bordered">
			<thead>
				<th scope="row">No</th>
				<th>ESTUDIANTE</th>
				<th>IDENTIFICACIÓN</th>
				<th>DESERTÓ MÓDULO 1</th>
				<th>
					<table class="table-condensed table-bordered">
						<tr>
							<th colspan="4" style="text-align: center;">MÓDULO 1 (2 EGB)</th>
						</tr>
						<tr>
							<th colspan="2" style="text-align: center;">Unidad 1</th>
							<th colspan="2" style="text-align: center;">Unidad 2</th>
						</tr>
						<tr>
							<th class="col-md-1">P1</th>
							<th class="col-md-1">P2</th>
							<th class="col-md-1">P3</th>
							<th class="col-md-1">P4</th>
						</tr>
					</table>
				</th>
				<th>PROMEDIO MÓDULO 1</th>
				<th>EVALUACIÓN COMPORTAMIENTO MÓDULO 1</th>
				<th>DESERTÓ MÓDULO 2</th>
				<th>
					<table class="table-condensed table-bordered">
						<tr>
							<th colspan="4" style="text-align: center;">MÓDULO 2 (3 EGB)</th>
						</tr>
						<tr>
							<th colspan="2" style="text-align: center;">Unidad 1</th>
							<th colspan="2" style="text-align: center;">Unidad 2</th>
						</tr>
						<tr>
							<th class="col-md-1">P1</th>
							<th class="col-md-1">P2</th>
							<th class="col-md-1">P3</th>
							<th class="col-md-1">P4</th>
						</tr>
					</table>
				</th>
				<th>PROMEDIO MÓDULO 2</th>
				<th>EVALUACIÓN COMPORTAMIENTO MÓDULO 2</th>
				<th>NOTA FINAL</th>
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
								<input type="checkbox" name="dm1-{{ $mat->codigo_inscripcion }}" id="dm1-{{ $mat->codigo_inscripcion }}" onchange="marcar_desercion(1,{{ $mat->codigo_inscripcion }},this);document.getElementById('hdm1-{{ $mat->codigo_inscripcion }}').value = this.checked;" 
								@if($mat->modulo_1_desertado==true)
									checked="true" 
								@endif>
								<input type="hidden" id="hdm1-{{ $mat->codigo_inscripcion }}" name="hdm1-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_1_desertado }}">
							</td>
							<td>
								<table>
									<tr>
										<td>
											<input class="col-md-1 form-control nota" type="text" id="m1p1-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_nota_final({{ $mat->codigo_inscripcion }});" onclick="document.getElementById('hm1p1-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->modulo_1_parcial_1 }}" 
											@if($mat->modulo_1_desertado==true)
												disabled="true" 
											@endif>

								
											<input type="hidden" name="hm1p1-{{ $mat->codigo_inscripcion }}" id="hm1p1-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_1_parcial_1 }}">
										</td>
										<td>
											<input class="col-md-1 form-control nota" type="text" id="m1p2-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_nota_final({{ $mat->codigo_inscripcion }});document.getElementById('hm1p2-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->modulo_1_parcial_2 }}" 
											@if($mat->modulo_1_desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hm1p2-{{ $mat->codigo_inscripcion }}" id="hm1p2-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_1_parcial_2 }}">
										</td>
										<td>
											<input class="col-md-1 form-control nota" type="text" name="m1p3-{{ $mat->codigo_inscripcion }}" id="m1p3-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_nota_final({{ $mat->codigo_inscripcion }});document.getElementById('hm1p3-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->modulo_1_parcial_3 }}" 
											@if($mat->modulo_1_desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hm1p3-{{ $mat->codigo_inscripcion }}" id="hm1p3-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_1_parcial_3 }}">
										</td>
										<td>
											<input class="col-md-1 form-control nota" type="text" id="m1p4-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_nota_final({{ $mat->codigo_inscripcion }});document.getElementById('hm1p4-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->modulo_1_parcial_4 }}" 
											@if($mat->modulo_1_desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hm1p4-{{ $mat->codigo_inscripcion }}" id="hm1p4-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_1_parcial_4 }}">
										</td>
									</tr>
								</table>
							</td>
							<td>
								<input class="col-md-1 form-control nota" type="text" id="pm1-{{ $mat->codigo_inscripcion }}" maxlength="5" disabled="true" value="{{ $mat->modulo_1_promedio }}">
								<input type="hidden" name="hpm1-{{ $mat->codigo_inscripcion }}" id="hpm1-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_1_promedio }}">
							</td>
							<td>
								<select style="font-size: 11px; height: 25px; width: 120px;" class="form-control" id="m1evcomp-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_1_comportamiento }}" onchange="calcular_nota_final({{ $mat->codigo_inscripcion }});" onclick="document.getElementById('hm1evcomp-{{ $mat->codigo_inscripcion }}').value = this.value;" 
									@if($mat->modulo_1_desertado=='SI') 
										disabled="true" 
									@endif>
									<option value="-1">seleccionar</option>
									<option value="A" 
									@if($mat->modulo_1_comportamiento=='A') 
										selected="selected" 
									@endif>Muy Satisfactorio</option>
									<option value="B" 
									@if($mat->modulo_1_comportamiento=='B') 
										selected="selected" 
									@endif>Satisfactorio</option>
									<option value="C" 
									@if($mat->modulo_1_comportamiento=='C') 
										selected="selected" 
									@endif>Poco Satisfactorio</option>
									<option value="D" 
									@if($mat->modulo_1_comportamiento=='D') 
										selected="selected" 
									@endif>Mejorable</option>
									<option value="E" 
									@if($mat->modulo_1_comportamiento=='E') 
										selected="selected" 
									@endif>Insatisfactorio</option>
								</select>
								<input type="hidden" name="hm1evcomp-{{ $mat->codigo_inscripcion }}" id="hm1evcomp-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_1_comportamiento }}">
							</td>
							<td>
								<input type="checkbox" id="dm2-{{ $mat->codigo_inscripcion }}" onchange="marcar_desercion(2,{{ $mat->codigo_inscripcion }},this);document.getElementById('hdm2-{{ $mat->codigo_inscripcion }}').value = this.checked;"
								@if($mat->modulo_1_desertado==true) 
									disabled="true"
								@elseif($mat->modulo_2_desertado==true)
									checked="true" 
								@endif>
								<input type="hidden" id="hdm2-{{ $mat->codigo_inscripcion }}" name="hdm2-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_2_desertado }}">
							</td>
							<td>
								<table>
									<tr>
										<td>
											<input class="col-xs-1 form-control nota" type="text" id="m2p1-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_nota_final({{ $mat->codigo_inscripcion }});document.getElementById('hm2p1-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->modulo_2_parcial_1 }}" 
											@if($mat->modulo_1_desertado==true or $mat->modulo_2_desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hm2p1-{{ $mat->codigo_inscripcion }}" id="hm2p1-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_2_parcial_1 }}">
										</td>
										<td>
											<input class="col-xs-1 form-control nota" type="text" id="m2p2-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_nota_final({{ $mat->codigo_inscripcion }});document.getElementById('hm2p2-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->modulo_2_parcial_2 }}" 
											@if($mat->modulo_1_desertado==true or $mat->modulo_2_desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hm2p2-{{ $mat->codigo_inscripcion }}" id="hm2p2-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_2_parcial_2 }}">
										</td>
										<td>
											<input class="col-xs-1 form-control nota" type="text" id="m2p3-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_nota_final({{ $mat->codigo_inscripcion }});document.getElementById('hm2p3-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->modulo_2_parcial_3 }}" 
											@if($mat->modulo_1_desertado==true or $mat->modulo_2_desertado==true) 
												disabled="true" 
											@endif>
											<input type="hidden" name="hm2p3-{{ $mat->codigo_inscripcion }}" id="hm2p3-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_2_parcial_3 }}">
										</td>
										<td>
											<input class="col-xs-1 form-control nota" type="text" id="m2p4-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_nota_final({{ $mat->codigo_inscripcion }});document.getElementById('hm2p4-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->modulo_2_parcial_4 }}" 
											@if($mat->modulo_1_desertado==true or $mat->modulo_2_desertado==true) 
												disabled="true" 
											@endif>
											<input type="hidden" name="hm2p4-{{ $mat->codigo_inscripcion }}" id="hm2p4-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_2_parcial_4 }}">
										</td>
									</tr>
								</table>
							</td>
							<td>
								<input class="col-md-1 form-control nota" type="text" id="pm2-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold;" disabled="true" value="{{ $mat->modulo_2_promedio }}">
								<input type="hidden" name="hpm2-{{ $mat->codigo_inscripcion }}" id="hpm2-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_2_promedio }}">
							</td>
							<td>
								<select style="font-size: 11px; height: 25px; width: 120px;" class="form-control" id="m2evcomp-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_2_comportamiento }}" onchange="calcular_nota_final({{ $mat->codigo_inscripcion }});document.getElementById('hm2evcomp-{{ $mat->codigo_inscripcion }}').value = this.value;" 
									@if($mat->modulo_1_desertado==true or $mat->modulo_2_desertado==true)
										disabled="true" 
									@endif>
									<option value="-1">seleccionar</option>
									<option value="A" 
									@if($mat->modulo_2_comportamiento=='A') 
										selected="selected" 
									@endif>Muy Satisfactorio</option>
									<option value="B" 
									@if($mat->modulo_2_comportamiento=='B') 
										selected="selected" 
									@endif>Satisfactorio</option>
									<option value="C" 
									@if($mat->modulo_2_comportamiento=='C') 
										selected="selected" 
									@endif>Poco Satisfactorio</option>
									<option value="D" 
									@if($mat->modulo_2_comportamiento=='D') 
										selected="selected" 
									@endif>Mejorable</option>
									<option value="E" 
									@if($mat->modulo_2_comportamiento=='E') 
										selected="selected" 
									@endif>Insatisfactorio</option>
								</select>
								<input type="hidden" name="hm2evcomp-{{ $mat->codigo_inscripcion }}" id="hm2evcomp-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_2_comportamiento }}">
							</td>
							<td>
								<input class="col-md-1 form-control nota nota_fin" type="text" id="nf-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" value="{{ $mat->nota_final }}" disabled="true">
								<input type="hidden" name="hnf-{{ $mat->codigo_inscripcion }}" id="hnf-{{ $mat->codigo_inscripcion }}" value="{{ $mat->nota_final }}">
							</td>
							<td>
								<input class="col-md-1 form-control nota" type="text" id="em-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_estado }}" disabled="true" 
								@if($mat->modulo_estado=='D') 
									style="font-weight: bold;background-color:orange;" 
								@elseif($mat->modulo_estado=='NP')
									style="font-weight: bold;background-color:#ffb3b3;"
								@elseif($mat->modulo_estado=='P')
									style="font-weight: bold;background-color:#b3ffcc;"
								@else
									style="font-weight: bold;background-color:transparent;"
								@endif>
								<input type="hidden" name="hem-{{ $mat->codigo_inscripcion }}" id="hem-{{ $mat->codigo_inscripcion }}" value="{{ $mat->modulo_estado }}">
							</td>
						</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	</form>
	<button type="button" class="btn btn-primary" onclick="guardar(event);">Guardar Calificaciones</button>
</div>
@endsection