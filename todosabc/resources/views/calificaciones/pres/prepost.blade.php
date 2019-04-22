@extends('layouts.app')

@section('content')
<div class="container-fluid">
	@if(session('estadoK')==400)
		<div class="alert alert-danger alert-dismissible" role="alert">
			Falta completar el registro de calificaciones
		</div>
	@endif
			@if (isset($materias_m1))
			<div class="panel panel-primary">
			<div class="panel-heading">Parámetros de {{ session('oferta')->nombre }} Módulo 2</div>
				<div class="panel-body">
					<form class="form-inline" method="GET" action="{{ route('calificaciones_post') }}">
						<div class="form-group">
							<label for="sel_asignatura">Asignatura:</label>
							<select id="sel_asignatura" name="bloque" class="form-control" style="width: 250px; font-size: 10px;">
								@foreach($materias_m1 as $materia)
								<option value="{{ $materia->id }}">{{ strtoupper($materia->asignatura) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label for="sel_ie">Institución:</label>
							<select id="sel_ie" name="id_institucion" class="form-control" style="width: 450px; font-size: 10px;">
								@foreach($ies as $ie)
								<option value="{{ $ie->id }}">{{ strtoupper($ie->institucion) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-success" value="Enviar"></input>
						</div>
					</form>
				</div>
			</div>
			@endif

			@if (isset($materias_m2))
			<div class="panel panel-primary">
			<div class="panel-heading">Parámetros de {{ session('oferta')->nombre }} Módulo 3</div>
				<div class="panel-body">
					<form class="form-inline" method="GET" action="{{ route('calificaciones_post') }}">
						<div class="form-group">
							<label for="sel_asignatura">Asignatura:</label>
							<select id="sel_asignatura" name="bloque" class="form-control" style="width: 250px; font-size: 10px;">
								@foreach($materias_m2 as $materia)
								<option value="{{ $materia->id }}">{{ strtoupper($materia->asignatura) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label for="sel_ie">Institución:</label>
							<select id="sel_ie" name="id_institucion" class="form-control" style="width: 450px; font-size: 10px;">
								@foreach($ies as $ie)
								<option value="{{ $ie->id }}">{{ strtoupper($ie->institucion) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-success" value="Enviar"></input>
						</div>
					</form>
				</div>
			</div>
			@endif

			@if (isset($materias_m3))
			<div class="panel panel-primary">
			<div class="panel-heading">Parámetros de {{ session('oferta')->nombre }} Módulo 4</div>
				<div class="panel-body">
					<form class="form-inline" method="GET" action="{{ route('calificaciones_post') }}">
						<div class="form-group">
							<label for="sel_asignatura">Asignatura:</label>
							<select id="sel_asignatura" name="bloque" class="form-control" style="width: 250px; font-size: 10px;">
								@foreach($materias_m3 as $materia)
								<option value="{{ $materia->id }}">{{ strtoupper($materia->asignatura) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label for="sel_ie">Institución:</label>
							<select id="sel_ie" name="id_institucion" class="form-control" style="width: 450px; font-size: 10px;">
								@foreach($ies as $ie)
								<option value="{{ $ie->id }}">{{ strtoupper($ie->institucion) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-success" value="Enviar"></input>
						</div>
					</form>
				</div>
			</div>
			@endif
			@if (isset($materias_m3_cont))
			<div class="panel panel-primary">
			<div class="panel-heading">Parámetros de {{ session('oferta')->nombre }}  6 meses - Módulo 3 (5-6 EGB) Continuidad</div>
				<div class="panel-body">
					<form class="form-inline" method="GET" action="{{ route('calificaciones_post') }}">
						<div class="form-group">
							<label for="sel_asignatura">Asignatura:</label>
							<select id="sel_asignatura" name="bloque" class="form-control" style="width: 250px; font-size: 10px;">
								@foreach($materias_m3_cont as $materia)
								<option value="{{ $materia->id }}">{{ strtoupper($materia->asignatura) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label for="sel_ie">Institución:</label>
							<select id="sel_ie" name="id_institucion" class="form-control" style="width: 450px; font-size: 10px;">
								@foreach($ies as $ie)
								<option value="{{ $ie->id }}">{{ strtoupper($ie->institucion) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-success" value="Enviar"></input>
						</div>
					</form>
				</div>
			</div>
			@endif

			@if (isset($materias_m4_cont))
			<div class="panel panel-primary">
			<div class="panel-heading">Parámetros de {{ session('oferta')->nombre }} 6 meses - Módulo 4 (7 EGB) Continuidad</div>
				<div class="panel-body">
					<form class="form-inline" method="GET" action="{{ route('calificaciones_post') }}">
						<div class="form-group">
							<label for="sel_asignatura">Asignatura:</label>
							<select id="sel_asignatura" name="bloque" class="form-control" style="width: 250px; font-size: 10px;">
								@foreach($materias_m4_cont as $materia)
								<option value="{{ $materia->id }}">{{ strtoupper($materia->asignatura) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label for="sel_ie">Institución:</label>
							<select id="sel_ie" name="id_institucion" class="form-control" style="width: 450px; font-size: 10px;">
								@foreach($ies as $ie)
								<option value="{{ $ie->id }}">{{ strtoupper($ie->institucion) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-success" value="Enviar"></input>
						</div>
					</form>
				</div>
			</div>
			@endif
			@if (isset($materias_m5_cont))
			<div class="panel panel-primary">
			<div class="panel-heading">Parámetros de {{ session('oferta')->nombre }} 6 meses - Módulo 4 (7 EGB) Continuidad</div>
				<div class="panel-body">
					<form class="form-inline" method="GET" action="{{ route('calificaciones_post') }}">
						<div class="form-group">
							<label for="sel_asignatura">Asignatura:</label>
							<select id="sel_asignatura" name="bloque" class="form-control" style="width: 250px; font-size: 10px;">
								@foreach($materias_m5_cont as $materia)
								<option value="{{ $materia->id }}">{{ strtoupper($materia->asignatura) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label for="sel_ie">Institución:</label>
							<select id="sel_ie" name="id_institucion" class="form-control" style="width: 450px; font-size: 10px;">
								@foreach($ies as $ie)
								<option value="{{ $ie->id }}">{{ strtoupper($ie->institucion) }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-success" value="Enviar"></input>
						</div>
					</form>
				</div>
			</div>
			@endif

		</div>
	</div>
</div>
@endsection