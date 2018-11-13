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
					FORMATO AUXILIAR DE NOTAS PARCIALES PARA {{ strtoupper($oferta->nombre) }} POR ASIGNATURA
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
				<label style="font-weight: bold; font-size: 12px;">GRADOS/NIVELES:</label>
			</td>
			<td>
				<label>{{ strtoupper($fase->nombre) }}</label>
			</td>
			<td>
				<label style="font-weight: bold; font-size: 12px;">PARALELO:</label>
			</td>
			<td>
				<label>{{ $paralelo }}</label>
			</td>
		</tr>
		<tr>
			<td>
				<label style="font-weight: bold; font-size: 12px;">ASIGNATURA:</label>
			</td>
			<td>{{ strtoupper($asignatura->nombre) }}</td>
			<td>
				<label style="font-weight: bold; font-size: 12px;">DOCENTE:</label>
			</td>
			<td>{{ strtoupper($docente->apellidos) }} {{ strtoupper($docente->nombres) }}</td>
			<td colspan="2">
				<a href="{{ route('calificaciones_bachillerato', ['descargar'=>'pdf-bachillerato', 'bloque' => $id_materia_oferta, 'doc' => $docente->id, 'par' => $paralelo, 'id_fase' => $fase->id]) }}">
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
	<form id="frm_guardar_bachillerato" class="form-horizontal" method="POST" action="{{ route('guardarCalifBachillerato') }}">
		{{ csrf_field() }}
		<input type="hidden" name="id_materia_oferta" value="{{ $id_materia_oferta }}">
		<input type="hidden" name="id_docente" value="{{ $docente->id }}">
		<input type="hidden" name="paralelo" value="{{ $paralelo }}">
		<input type="hidden" name="id_fase" value="{{ $fase->id }}">
		<table class="table table-hover table-condensed table-bordered">
			<thead>
				<th scope="row">No</th>
				<th>ESTUDIANTE</th>
				<th>IDENTIFICACIÓN</th>
				<th class="col-md-1">DESERTÓ</th>
				<th>
					<table width="560">
						<tr>
							<th colspan="4" style="text-align: center;">QUIMESTRE 1 (Q1)</th>
						</tr>
						<tr>
							<th>
								<table class="table-condensed table-bordered">
									<tr>
										<th colspan="5" style="text-align: center;">PROCESO FORMATIVO</th>
									</tr>
									<tr>
										<th class="col-md-1">P 1</th>
										<th class="col-md-1">P 2</th>
										<th class="col-md-1">Nota 10/10</th>
										<th class="col-md-1">Nota 80%</th>
									</tr>
								</table>
							</th>
							<th>
								<table class="table-condensed table-bordered">
									<tr>
										<th colspan="2" style="text-align: center;">PROCESO SUMATIVO</th>
									</tr>
									<tr>
										<th class="col-md-1">Nota 10/10</th>
										<th class="col-md-1">Nota 20%</th>
									</tr>
								</table>
							</th>
							<th class="col-md-1" style="text-align: center;">NOTA Q1</th>
							<th class="col-md-1">EV. COMPORT.</th>
						</tr>
					</table>
				</th>
				<th>
					<table width="560">
						<tr>
							<th colspan="4" style="text-align: center;">QUIMESTRE 2 (Q2)</th>
						</tr>
						<tr>
							<th>
								<table class="table-condensed table-bordered">
									<tr>
										<th colspan="5" style="text-align: center;">PROCESO FORMATIVO</th>
									</tr>
									<tr>
										<th class="col-md-1">P 1</th>
										<th class="col-md-1">P 2</th>
										<th class="col-md-1">Nota 10/10</th>
										<th class="col-md-1">Nota 80%</th>
									</tr>
								</table>
							</th>
							<th>
								<table class="table-condensed table-bordered">
									<tr>
										<th colspan="2" style="text-align: center;">PROCESO SUMATIVO</th>
									</tr>
									<tr>
										<th class="col-md-1">Nota 10/10</th>
										<th class="col-md-1">Nota 20%</th>
									</tr>
								</table>
							</th>
							<th class="col-md-1" style="text-align: center;">NOTA Q2</th>
							<th class="col-md-1">EV. COMPORT.</th>
						</tr>
					</table>
				</th>
				<th class="col-md-1">
					<table class="table-condensed table-bordered">
						<tr>
							<th colspan="3" style="text-align: center;">EX EXTRAS</th>
						</tr>
						<tr>
							<th style="text-align: center;">SUPLETORIO</th>
							<th style="text-align: center;">REMEDIAL</th>
							<th style="text-align: center;">GRACIA</th>
						</tr>
					</table>
				</th>
				<th class="col-md-1">PROMEDIO FINAL (Q1 + Q2) / 2</th>
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
								<input type="checkbox" name="ds-{{ $mat->codigo_inscripcion }}" id="ds-{{ $mat->codigo_inscripcion }}" onchange="desertar('{{ $mat->codigo_inscripcion }}',this);" 
								@if($mat->desertado==true)
									checked="true" 
								@endif>
							</td>
							<td>
								<table>
									<tr>
										<td>
											<input class="col-md-1 form-control nota-bachillerato" type="text" id="q1p1-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_pm_parciales_q1('{{ $mat->codigo_inscripcion }}');document.getElementById('hq1p1-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->quimestre_1_parcial_1 }}" 
											@if($mat->desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hq1p1-{{ $mat->codigo_inscripcion }}" id="hq1p1-{{ $mat->codigo_inscripcion }}" value="{{ $mat->quimestre_1_parcial_1 }}">
										</td>
										<td>
											<input class="col-md-1 form-control nota-bachillerato" type="text" id="q1p2-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_pm_parciales_q1('{{ $mat->codigo_inscripcion }}');document.getElementById('hq1p2-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->quimestre_1_parcial_2 }}" 
											@if($mat->desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hq1p2-{{ $mat->codigo_inscripcion }}" id="hq1p2-{{ $mat->codigo_inscripcion }}" value="{{ $mat->quimestre_1_parcial_2 }}">
										</td>
										<td>
											<input class="col-md-1 form-control bachillerato-formativo" type="text" id="q1pmpar-{{ $mat->codigo_inscripcion }}" maxlength="5" disabled="true" value="{{ $mat->quimestre_1_promedio_parciales }}">
											<input type="hidden" name="hq1pmpar-{{ $mat->codigo_inscripcion }}" id="hq1pmpar-{{ $mat->codigo_inscripcion }}" maxlength="5" value="{{ $mat->quimestre_1_promedio_parciales }}">
										</td>
										<td>
											<input class="col-md-1 form-control bachillerato-formativo" type="text" id="q1pmgpar-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" value="{{ bcdiv($mat->quimestre_1_promedio_parciales * 0.80,'1',2) }}" disabled="true">
										</td>
										<td>
											<input class="col-md-1 form-control bachillerato-sumativo" type="text" id="ex1-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_nota20_q1('{{ $mat->codigo_inscripcion }}');document.getElementById('hex1-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->quimestre_1_examen }}" 
											@if($mat->desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hex1-{{ $mat->codigo_inscripcion }}" id="hex1-{{ $mat->codigo_inscripcion }}" value="{{ $mat->quimestre_1_examen }}">
										</td>
										<td>
											<input class="col-md-1 form-control bachillerato-sumativo" type="text" id="exs1-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" value="{{ bcdiv($mat->quimestre_1_examen * 0.20,'1',2) }}" disabled="true">
										</td>
										<td>
											<input class="col-md-1 form-control nota-bachillerato" type="text" id="pmfq1-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" value="{{ bcdiv($mat->quimestre_1_final,'1',2) }}" disabled="true">
											<input type="hidden" name="hpmfq1-{{ $mat->codigo_inscripcion }}" id="hpmfq1-{{ $mat->codigo_inscripcion }}" maxlength="5" value="{{ $mat->quimestre_1_final }}">
										</td>
										<td>
											<select style="font-size: 11px; height: 25px; width: 135px;" class="form-control" id="compq1-{{ $mat->codigo_inscripcion }}" onchange="document.getElementById('hcompq1-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->quimestre_1_comportamiento }}" 
												@if($mat->desertado==true)
													disabled="true" 
												@endif>
												<option value="-1">seleccionar</option>
												<option value="A" 
												@if($mat->quimestre_1_comportamiento=='A') 
													selected="selected" 
												@endif>Muy Satisfactorio</option>
												<option value="B" 
												@if($mat->quimestre_1_comportamiento=='B') 
													selected="selected" 
												@endif>Satisfactorio</option>
												<option value="C" 
												@if($mat->quimestre_1_comportamiento=='C') 
													selected="selected" 
												@endif>Poco Satisfactorio</option>
												<option value="D" 
												@if($mat->quimestre_1_comportamiento=='D') 
													selected="selected" 
												@endif>Mejorable</option>
												<option value="E" 
												@if($mat->quimestre_1_comportamiento=='E') 
													selected="selected" 
												@endif>Insatisfactorio</option>
											</select>
											<input type="hidden" name="hcompq1-{{ $mat->codigo_inscripcion }}" id="hcompq1-{{ $mat->codigo_inscripcion }}" value="{{ $mat->quimestre_1_comportamiento }}">
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table>
									<tr>
										<td>
											<input class="col-md-1 form-control nota-bachillerato" type="text" id="q2p1-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_pm_parciales_q2('{{ $mat->codigo_inscripcion }}');document.getElementById('hq2p1-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->quimestre_2_parcial_1 }}" 
											@if($mat->desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hq2p1-{{ $mat->codigo_inscripcion }}" id="hq2p1-{{ $mat->codigo_inscripcion }}" value="{{ $mat->quimestre_2_parcial_1 }}">
										</td>
										<td>
											<input class="col-md-1 form-control nota-bachillerato" type="text" id="q2p2-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_pm_parciales_q2('{{ $mat->codigo_inscripcion }}');document.getElementById('hq2p2-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->quimestre_2_parcial_2 }}" 
											@if($mat->desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hq2p2-{{ $mat->codigo_inscripcion }}" id="hq2p2-{{ $mat->codigo_inscripcion }}" value="{{ $mat->quimestre_2_parcial_2 }}">
										</td>
										<td>
											<input class="col-md-1 form-control bachillerato-formativo" type="text" id="q2pmpar-{{ $mat->codigo_inscripcion }}" maxlength="5" disabled="true" value="{{ $mat->quimestre_2_promedio_parciales }}">
											<input type="hidden" name="hq2pmpar-{{ $mat->codigo_inscripcion }}" id="hq2pmpar-{{ $mat->codigo_inscripcion }}" maxlength="5" value="{{ $mat->quimestre_2_promedio_parciales }}">
										</td>
										<td>
											<input class="col-md-1 form-control bachillerato-formativo" type="text" id="q2pmgpar-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" value="{{ bcdiv($mat->quimestre_2_promedio_parciales * 0.80,'1',2) }}" disabled="true">
										</td>
										<td>
											<input class="col-md-1 form-control bachillerato-sumativo" type="text" id="ex2-{{ $mat->codigo_inscripcion }}" maxlength="5" onblur="calcular_nota20_q2('{{ $mat->codigo_inscripcion }}');document.getElementById('hex2-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->quimestre_2_examen }}" 
											@if($mat->desertado==true)
												disabled="true" 
											@endif>
											<input type="hidden" name="hex2-{{ $mat->codigo_inscripcion }}" id="hex2-{{ $mat->codigo_inscripcion }}" value="{{ $mat->quimestre_2_examen }}">
										</td>
										<td>
											<input class="col-md-1 form-control bachillerato-sumativo" type="text" id="exs2-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" value="{{ bcdiv($mat->quimestre_2_examen * 0.20,'1',2) }}" disabled="true">
										</td>
										<td>
											<input class="col-md-1 form-control nota-bachillerato" type="text" id="pmfq2-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" value="{{ bcdiv($mat->quimestre_2_final,'1',2) }}" disabled="true">
											<input type="hidden" name="hpmfq2-{{ $mat->codigo_inscripcion }}" id="hpmfq2-{{ $mat->codigo_inscripcion }}" maxlength="5" value="{{ $mat->quimestre_2_final }}">
										</td>
										<td>
											<select style="font-size: 11px; height: 25px; width: 135px;" class="form-control" id="compq2-{{ $mat->codigo_inscripcion }}" onchange="document.getElementById('hcompq2-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ $mat->quimestre_2_comportamiento }}" 
												@if($mat->desertado==true)
													disabled="true" 
												@endif>
												<option value="-1">seleccionar</option>
												<option value="A" 
												@if($mat->quimestre_2_comportamiento=='A') 
													selected="selected" 
												@endif>Muy Satisfactorio</option>
												<option value="B" 
												@if($mat->quimestre_2_comportamiento=='B') 
													selected="selected" 
												@endif>Satisfactorio</option>
												<option value="C" 
												@if($mat->quimestre_2_comportamiento=='C') 
													selected="selected" 
												@endif>Poco Satisfactorio</option>
												<option value="D" 
												@if($mat->quimestre_2_comportamiento=='D') 
													selected="selected" 
												@endif>Mejorable</option>
												<option value="E" 
												@if($mat->quimestre_2_comportamiento=='E') 
													selected="selected" 
												@endif>Insatisfactorio</option>
											</select>
											<input type="hidden" name="hcompq2-{{ $mat->codigo_inscripcion }}" id="hcompq2-{{ $mat->codigo_inscripcion }}" value="{{ $mat->quimestre_2_comportamiento }}">
										</td>
									</tr>
								</table>
							</td>
							<td>
								<input class="col-md-1 form-control basica-promedio" type="text" id="sup-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" onblur="habilitar_remedial('{{ $mat->codigo_inscripcion }}', this); recalcular_nota_final('{{ $mat->codigo_inscripcion }}', this); document.getElementById('hsup-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ bcdiv($mat->supletorio,'1',2) }}" 
								@if($mat->desertado==true)
									disabled="disabled"
								@endif>						
								<input class="col-md-1 form-control basica-promedio" type="text" id="rem-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" onblur="habilitar_gracia('{{ $mat->codigo_inscripcion }}', this); recalcular_nota_final('{{ $mat->codigo_inscripcion }}', this); document.getElementById('hrem-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ bcdiv($mat->remedial,'1',2) }}"
								@if($mat->desertado==true)
									disabled="disabled"
								@endif>
								<input class="col-md-1 form-control basica-promedio" type="text" id="grac-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;" onblur="recalcular_nota_final_con_ex_gracia('{{ $mat->codigo_inscripcion }}', this); document.getElementById('hgrac-{{ $mat->codigo_inscripcion }}').value = this.value;" value="{{ bcdiv($mat->gracia,'1',2) }}"
								@if($mat->desertado==false and $mat->id_materia_oferta_gracia == 1)
									
								@elseif($mat->desertado==false and $mat->id_materia_oferta_gracia == $id_materia_oferta)

								@else
									disabled="disabled"
								@endif>
								<input type="hidden" name="hsup-{{ $mat->codigo_inscripcion }}" id="hsup-{{ $mat->codigo_inscripcion }}" value="{{ bcdiv($mat->supletorio,'1',2) }}">
								<input type="hidden" name="hrem-{{ $mat->codigo_inscripcion }}" id="hrem-{{ $mat->codigo_inscripcion }}" value="{{ bcdiv($mat->remedial,'1',2) }}">
								<input type="hidden" name="hgrac-{{ $mat->codigo_inscripcion }}" id="hgrac-{{ $mat->codigo_inscripcion }}" value="{{ bcdiv($mat->gracia,'1',2) }}">
							</td>							
							<td>
								<input class="col-md-1 form-control nota" type="text" id="nf-{{ $mat->codigo_inscripcion }}" maxlength="5" style="font-weight: bold !important;@if($mat->nota_final<7) color:red;@endif" value="{{ bcdiv($mat->nota_final,'1',2) }}" disabled="true">
								<input type="hidden" name="hnf-{{ $mat->codigo_inscripcion }}" id="hnf-{{ $mat->codigo_inscripcion }}" value="{{ bcdiv($mat->nota_final,'1',2) }}">
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
		<button type="button" class="btn btn-primary" onclick="guardar_bachillerato(event);">Guardar Calificaciones</button>
	@endif
	<a class="btn btn-default" href="{{ route('calificaciones_prebachillerato', ['token' => session('token')]) }}">Regresar</a>	
</div>
@endsection