<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Modelo\CalificacionAlfa;
use App\Modelo\CalificacionPost;
use App\Modelo\CalificacionBasica;
use App\Modelo\CalificacionBachillerato;
use App\Modelo\MateriaOferta;
use App\Modelo\Matriculado;
use App\Modelo\FaseOferta;
use App\Modelo\Docente;
use App\Modelo\Oferta;
use App\Modelo\EstadoParticipante;
use PDF;

class CalificacionController extends Controller
{
	private $ie;
	private $fases;
	private $docentes;
	private $paralelos;
	private $calificaciones_alfa;
	private $calificaciones_post;
	private $calificaciones_basica;
	private $calificaciones_bachillerato;
	private $docentes_asignaturas;
	private $ultimo_anio_aprobado;

	public function bachillerato_fases(Request $request)
    {
      $ies = DB::table('todosabc.instituciones')
      ->where('id_usuario', session('user')->id)
      ->get();

      $ids_ies = array();

      foreach ($ies as $ie) {
      	array_push($ids_ies, $ie->id);
     	 }

      $matriculados = DB::table('todosabc.matriculados')
      ->whereIn('id_institucion', $ids_ies)
      ->where('asiste_con_frecuencia', true)
      ->get();

      foreach($matriculados as $m){
      	  $mat = Matriculado::find($m->id);
          $codmat = $mat->codigo_inscripcion;
          $mat->fase1 = $request->input('f1-'.$codmat);
          $mat->fase2 = $request->input('f2-'.$codmat);
          $mat->fase3 = $request->input('f3-'.$codmat);
          $mat->update();

          $fases = DB::table('todosabc.fases_oferta')
          ->where('id_oferta', $mat->id_oferta)
          ->orderBy('id')
          ->get();

//           $ultimo_anio_aprobado = DB::table('todosabc.matriculados')
  //        ->where('id_oferta', $mat->id_oferta)
    //      ->orderBy('id')
      //    ->get();
          if($mat->fase1==null || $mat->fase1==false){
          	$calificaciones_bachillerato = DB::table('todosabc.calificaciones_bachillerato')
          	->where('id_matriculado', $mat->id)
          	->where('id_oferta_fase', $fases[0]->id)
          	->delete();
          }

          if($mat->fase2==null || $mat->fase2==false){
          	$calificaciones_bachillerato = DB::table('todosabc.calificaciones_bachillerato')
          	->where('id_matriculado', $mat->id)
          	->where('id_oferta_fase', $fases[1]->id)
          	->delete();
          }

          if($mat->fase3==null || $mat->fase3==false){
          	$calificaciones_bachillerato = DB::table('todosabc.calificaciones_bachillerato')
          	->where('id_matriculado', $mat->id)
          	->where('id_oferta_fase', $fases[2]->id)
          	->delete();
          }
      }

      return redirect()->route('calificaciones_prebachillerato');
    }

	public function bachillerato(Request $request){
		DB::statement("UPDATE todosabc.calificaciones_bachillerato SET estado = 'D' WHERE desertado = true AND estado IS NULL");

		DB::statement("DELETE FROM todosabc.calificaciones_bachillerato WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (partition BY id_usuario, id_matriculado, id_materia_oferta, id_oferta_fase order by id desc) AS rnum FROM todosabc.calificaciones_bachillerato ) t WHERE t.rnum > 1)");

		$request->session()->put('estadoK', 0);
		$this->cargarInstitucionesDelUsuario();
		$fase = FaseOferta::find($request->input('id_fase'));
		$materia_oferta = MateriaOferta::find($request->input('bloque'));
		$docente = Docente::find($request->input('doc'));
		$oferta = Oferta::find($materia_oferta->id_oferta);
		$asignatura = DB::table('materias')->find($materia_oferta->id_materia);
		$paralelo = $request->input('par');

		$this->cargarEstudiantesBachillerato($materia_oferta, $docente->id, $paralelo, $fase);

		if($request->has('descargar')){
			$pdf = PDF::loadView('calificaciones.pdfs.pdf-bachillerato', ['ie' => $this->ie,
					   'estudiantes' => $this->calificaciones_bachillerato,
					   'oferta' => $oferta,
					   'asignatura' => $asignatura,
					   'id_materia_oferta' => $materia_oferta->id,
					   'docente' => $docente,
					   'paralelo' => $paralelo,
					   'fase' => $fase,
					  ])
			->setPaper('a4', 'landscape');
			return $pdf->download('calificaciones_bachillerato.pdf');
		}else{
			view()->share(['ie' => $this->ie,
					   'estudiantes' => $this->calificaciones_bachillerato,
					   'oferta' => $oferta,
					   'asignatura' => $asignatura,
					   'id_materia_oferta' => $materia_oferta->id,
					   'docente' => $docente,
					   'paralelo' => $paralelo,
					   'fase' => $fase,
					  ]);
		}

		return view('calificaciones.bachillerato');
	}

	public function prebachillerato(Request $request){
		DB::statement("UPDATE todosabc.calificaciones_bachillerato SET estado = 'D' WHERE desertado = true AND estado IS NULL");

		DB::statement("DELETE FROM todosabc.calificaciones_bachillerato WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (partition BY id_usuario, id_matriculado, id_materia_oferta, id_oferta_fase order by id desc) AS rnum FROM todosabc.calificaciones_bachillerato ) t WHERE t.rnum > 1)");

		$id_fase = null;
		$tiene_fase = false;
		$this->cargarFases();
		$this->cargarInstitucionesDelUsuario();

		if($this->ie == null){
          return redirect()->route('institucionesUsuario');
      	}

		$this->cargarDocentesUsuario();

		if($this->docentes == null or $this->docentes->count() == 0){
          return redirect()->route('docentesUsuario');
      	}

      	$this->cargarDocentesAsignaturas();

      	if($request->has('hfase') and $request->has('hfase')!=null){
      		$id_fase = $request->input('hfase');
      		$tiene_fase = true;
			$this->cargarParalelosEstudiantes();

			if($request->input('hfase')==4 or $request->input('hfase')==8 ){
		    	$materias = DB::table('todosabc.materias_oferta')
				->join('materias', 'materias_oferta.id_materia','=','materias.id')
				->select('todosabc.materias_oferta.*','materias.nombre as asignatura')
				->where('todosabc.materias_oferta.id_oferta', session('user')->id_oferta)
				->whereNotIn('todosabc.materias_oferta.id_materia', [6,7,10])
				->get();
			}else{
				$materias = DB::table('todosabc.materias_oferta')
				->join('materias', 'materias_oferta.id_materia','=','materias.id')
				->select('todosabc.materias_oferta.*','materias.nombre as asignatura')
				->where('todosabc.materias_oferta.id_oferta', session('user')->id_oferta)
				->get();
			}

			view()->share(['materias' => $materias,
		                   'docentes' => $this->docentes,
		                   'paralelos' => $this->paralelos,
		                   'fases' => $this->fases,
		                   'tiene_fase' => $tiene_fase,
		                   'id_fase' => $id_fase,
		                   'docentes_asignaturas' => $this->docentes_asignaturas,
		                  ]);
      	}else{
			view()->share(['fases' => $this->fases,
						   'tiene_fase' => $tiene_fase,
						   'id_fase' => $id_fase,
						   'docentes_asignaturas' => $this->docentes_asignaturas,
						  ]);
      	}

		return view('calificaciones.pres.prebachillerato2');
	}

	function cargarFases(){
		$this->fases = DB::table('todosabc.fases_oferta')
		->where('id_oferta', session('user')->id_oferta)
		->orderBy('nombre')
		->get();
	}

	public function prebasica(Request $request){
		if($request->has('token') and $request->input('token')==session('token')){

			CalificacionBasica::where('desertado',true)
			->where('estado',null)
			->update(['estado' =>'D']);

			//DB::statement("UPDATE todosabc.calificaciones_basica SET estado = 'D' WHERE desertado = true AND estado IS NULL");

			DB::statement("DELETE FROM todosabc.calificaciones_basica WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (partition BY id_usuario, id_matriculado, id_materia_oferta, id_fase order by id desc) AS rnum FROM todosabc.calificaciones_basica ) t WHERE t.rnum > 1)");

			$this->cargarInstitucionesDelUsuario();

			if($this->ie == null){
	          return redirect()->route('institucionesUsuario');
	      	}

			$this->cargarDocentesUsuario();

			if($this->docentes == null or $this->docentes->count() == 0){
	          return redirect()->route('docentesUsuario');
	      	}

			$this->cargarParalelosEstudiantes();

	    	$materias = DB::table('todosabc.materias_oferta')
			->join('materias', 'materias_oferta.id_materia','=','materias.id')
			->select('todosabc.materias_oferta.*','materias.nombre as asignatura')
			->where('id_oferta', session('user')->id_oferta)
			->get();

			$this->cargarDocentesAsignaturas();

			view()->share(['materias' => $materias,
		                   'docentes' => $this->docentes,
		                   'paralelos' => $this->paralelos,
		                   'docentes_asignaturas' => $this->docentes_asignaturas,
		                  ]);

			return view('calificaciones.pres.prebasica');
		}else{
			return redirect()->route('customLogin');
		}
	}

	public function basica(Request $request){


			CalificacionBasica::where('desertado',true)
			->where('estado',null)
			->update(['estado' =>'D']);

		//DB::statement("UPDATE todosabc.calificaciones_basica SET estado = 'D' WHERE desertado = true AND estado IS NULL");

		DB::statement("DELETE FROM todosabc.calificaciones_basica WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (partition BY id_usuario, id_matriculado, id_materia_oferta, id_fase order by id desc) AS rnum FROM todosabc.calificaciones_basica ) t WHERE t.rnum > 1)");

		$request->session()->put('estadoK', 0);
		$this->cargarInstitucionesDelUsuario();
		$materia_oferta = MateriaOferta::find($request->input('bloque'));
		$docente = Docente::find($request->input('doc'));
		$oferta = Oferta::find($materia_oferta->id_oferta);
		$asignatura = DB::table('materias')->find($materia_oferta->id_materia);
		$paralelo = $request->input('par');

		$this->cargarEstudiantesBasica($materia_oferta, $docente->id, $paralelo);

		if($request->has('descargar')){
			$pdf = PDF::loadView('calificaciones.pdfs.pdf-basica', ['ie' => $this->ie,
					   'estudiantes' => $this->calificaciones_basica,
					   'oferta' => $oferta,
					   'asignatura' => $asignatura,
					   'id_materia_oferta' => $materia_oferta->id,
					   'docente' => $docente,
					   'paralelo' => $paralelo,
					  ])
			->setPaper('a4', 'landscape');
			return $pdf->download('calificaciones_basica.pdf');
		}else{
			view()->share(['ie' => $this->ie,
					   'estudiantes' => $this->calificaciones_basica,
					   'oferta' => $oferta,
					   'asignatura' => $asignatura,
					   'id_materia_oferta' => $materia_oferta->id,
					   'docente' => $docente,
					   'paralelo' => $paralelo,
					  ]);
		}

		return view('calificaciones.basica');
	}

	public function prepost(Request $request){
		if($request->has('token') and $request->input('token')==session('token')){
			DB::statement("UPDATE todosabc.calificaciones_post SET estado = 'D' WHERE desertado = true AND estado IS NULL");

		    DB::statement("DELETE FROM todosabc.calificaciones_post WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (partition BY id_usuario, id_matriculado, id_materia_oferta order by id desc) AS rnum FROM todosabc.calificaciones_post ) t WHERE t.rnum > 1)");

			$ies = DB::table('todosabc.instituciones')
	    	->join('codigos_amie','codigos_amie.amie','=','todosabc.instituciones.amie')
	    	->select('todosabc.instituciones.id','codigos_amie.institucion')
	    	->where('id_usuario', session('user')->id)
	    	->get();

	    	$ids_ies = array();

	    	for($i = 0; $i < $ies->count(); $i++){
	    		$ids_ies[$i] = $ies[$i]->id;
	    	}

			$docente = DB::table('todosabc.docentes')
	    	->whereIn('id_institucion',$ids_ies)
	    	->get();

		    $estudiantes_post_m1 = DB::table('todosabc.matriculados')
	    	->where('id_docente', $docente[0]->id)
	    	->where('id_oferta', 3)
	    	->where('todosabc.matriculados.asiste_con_frecuencia', true)
	    	->get();

	    	$materias_m1 = null;

	    	if($estudiantes_post_m1->count()>0){
				$materias_m1 = DB::table('todosabc.materias_oferta')
				->join('materias', 'materias_oferta.id_materia','=','materias.id')
				->select('todosabc.materias_oferta.*','materias.nombre as asignatura')
				->where('id_oferta', 3)
				->get();
			}

			$estudiantes_post_m2 = DB::table('todosabc.matriculados')
	    	->where('id_docente', $docente[0]->id)
	    	->where('id_oferta', 4)
	    	->where('todosabc.matriculados.asiste_con_frecuencia', true)
	    	->get();

	    	$materias_m2 = null;

	    	if($estudiantes_post_m2->count()>0){
				$materias_m2 = DB::table('todosabc.materias_oferta')
				->join('materias', 'materias_oferta.id_materia','=','materias.id')
				->select('todosabc.materias_oferta.*','materias.nombre as asignatura')
				->where('id_oferta', 4)
				->get();
			}

			$estudiantes_post_m3 = DB::table('todosabc.matriculados')
	    	->where('id_docente', $docente[0]->id)
	    	->where('id_oferta', 5)
	    	->where('todosabc.matriculados.asiste_con_frecuencia', true)
	    	->get();

	    	$materias_m3 = null;

	    	if($estudiantes_post_m3->count()>0){
				$materias_m3 = DB::table('todosabc.materias_oferta')
				->join('materias', 'materias_oferta.id_materia','=','materias.id')
				->select('todosabc.materias_oferta.*','materias.nombre as asignatura')
				->where('id_oferta', 5)
				->get();
			}

			// estudiantes id_oferta=11

			$estudiantes_post_m3_cont = DB::table('todosabc.matriculados')
			->where('id_docente', $docente[0]->id)
			->where('id_oferta', 11)
			->where('todosabc.matriculados.asiste_con_frecuencia', true)
			 ->get();

			$materias_m3_cont = null;

			if ($estudiantes_post_m3_cont->count()>0){
				$materias_m3_cont = DB::table('todosabc.materias_oferta')
				->join('materias', 'materias_oferta.id_materia','=','materias.id')
				->select('todosabc.materias_oferta.*','materias.nombre as asignatura')
				->where('id_oferta', 11)
				->get();
			}

			$estudiantes_post_m4_cont = DB::table('todosabc.matriculados')
		    ->where('id_docente', $docente[0]->id)
			->where('id_oferta', 12)
			->where('todosabc.matriculados.asiste_con_frecuencia', true)
			->get();

				$materias_m4_cont = null;

			// estudiantes id_oferta=12
			if ($estudiantes_post_m4_cont->count()>0){
				$materias_m4_cont = DB::table('todosabc.materias_oferta')
				->join('materias', 'materias_oferta.id_materia','=','materias.id')
				->select('todosabc.materias_oferta.*','materias.nombre as asignatura')
				->where('id_oferta', 12)
				->get();
			}


			view()->share(['materias_m1' => $materias_m1,
						   'materias_m2' => $materias_m2,
						   'materias_m3' => $materias_m3,
						   'materias_m3_cont' => $materias_m3_cont,
						   'materias_m4_cont' => $materias_m4_cont,
			               'ies' => $ies,
			               ]);

			return view('calificaciones.pres.prepost');
		}else{
			return redirect()->route('customLogin');
		}
	}

	public function post(Request $request){
		DB::statement("UPDATE todosabc.calificaciones_post SET estado = 'D' WHERE desertado = true AND estado IS NULL");

		DB::statement("DELETE FROM todosabc.calificaciones_post WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (partition BY id_usuario, id_matriculado, id_materia_oferta order by id desc) AS rnum FROM todosabc.calificaciones_post ) t WHERE t.rnum > 1)");

		$request->session()->put('estadoK', 0);
		$ie = DB::table('todosabc.instituciones')
    	->join('codigos_amie','codigos_amie.amie','=','todosabc.instituciones.amie')
    	->select('todosabc.instituciones.id','codigos_amie.zona','codigos_amie.provincia','codigos_amie.canton','codigos_amie.distrito','codigos_amie.amie','codigos_amie.institucion')
    	->where('todosabc.instituciones.id', $request->input('id_institucion'))
    	->first();

		$materia_oferta = MateriaOferta::find($request->input('bloque'));
		$oferta = Oferta::find($materia_oferta->id_oferta);
		$asignatura = DB::table('materias')
		->find($materia_oferta->id_materia);

		$this->cargarEstudiantesPost($materia_oferta, $request->input('id_institucion'));

		if($request->has('descargar')){
			$pdf = PDF::loadView('calificaciones.pdfs.pdf-post', ['ie' => $ie,
					   'estudiantes' => $this->calificaciones_post,
					   'oferta' => $oferta,
					   'asignatura' => $asignatura,
					  ])
			->setPaper('a4', 'landscape');
			return $pdf->download('calificaciones_post.pdf');
		}else{
			view()->share(['ie' => $ie,
						   'estudiantes' => $this->calificaciones_post,
						   'oferta' => $oferta,
						   'asignatura' => $asignatura,
						   'id_materia_oferta' => $materia_oferta->id,
						  ]);
		}

		return view('calificaciones.post');
	}

	public function guardarBachillerato(Request $request){

		$this->cargarInstitucionesDelUsuario();
		$fase = FaseOferta::find($request->input('id_fase'));
		$materia_oferta = MateriaOferta::find($request->input('id_materia_oferta'));
		$docente = Docente::find($request->input('id_docente'));
		$oferta = Oferta::find($materia_oferta->id_oferta);
		$asignatura = DB::table('materias')->find($materia_oferta->id_materia);
		$paralelo = $request->input('paralelo');

		$this->cargarEstudiantesBachillerato($materia_oferta, $docente->id, $paralelo, $fase);

		foreach ($this->calificaciones_bachillerato as $cal) {
			$codin = $cal->codigo_inscripcion;
			$k = CalificacionBachillerato::find($cal->id);
			$k->fecha_registro = date('Y-m-d h:m:s');
			$k->registrado_por = session('user')->usuario;
			$k->quimestre_1_parcial_1 = $request->input('hq1p1-'.$codin);
			$k->quimestre_1_parcial_2 = $request->input('hq1p2-'.$codin);
			$k->quimestre_1_examen = $request->input('hex1-'.$codin);
			$k->quimestre_1_comportamiento = $request->input('hcompq1-'.$codin);
			$k->quimestre_1_promedio_parciales = $request->input('hq1pmpar-'.$codin);
			$k->quimestre_1_final = $request->input('hpmfq1-'.$codin);
			$k->quimestre_2_parcial_1 = $request->input('hq2p1-'.$codin);
			$k->quimestre_2_parcial_2 = $request->input('hq2p2-'.$codin);
			$k->quimestre_2_examen = $request->input('hex2-'.$codin);
			$k->quimestre_2_comportamiento = $request->input('hcompq2-'.$codin);
			$k->quimestre_2_promedio_parciales = $request->input('hq2pmpar-'.$codin);
			$k->quimestre_2_final = $request->input('hpmfq2-'.$codin);

			if($request->input('ds-'.$codin)==null){
				$k->desertado = false;
			}else{
				$k->desertado = $request->input('ds-'.$codin);
			}

    		$k->estado = $request->input('hem-'.$codin);
    		$k->nota_final = $request->input('hnf-'.$codin);
    		$k->supletorio = $request->input('hsup-'.$codin);
    		$k->remedial = $request->input('hrem-'.$codin);
    		$k->gracia = $request->input('hgrac-'.$codin);

    		if($k->gracia > 0){
    			$k->id_materia_oferta_gracia = $materia_oferta->id;
    		}else{
    			$k->id_materia_oferta_gracia = -1;
    		}

			if($k->desertado==true){
				CalificacionBachillerato::where('id_matriculado', $k->id_matriculado)
				->where('id_oferta_fase', $fase->id)
				->update(['fecha_registro' => date('Y-m-d h:m:s'),'desertado' => true,'id_materia_oferta_gracia' => $k->id_materia_oferta_gracia]);

			}else{
				CalificacionBachillerato::where('id_matriculado', $k->id_matriculado)
				->where('id_oferta_fase', $fase->id)
				->update(['fecha_registro' => date('Y-m-d h:m:s'),'desertado' => false,'id_materia_oferta_gracia' => $k->id_materia_oferta_gracia]);


				$no_desertado = Matriculado::find($k->id_matriculado);
				$no_desertado->razon_desercion = null;
				$no_desertado->update();
			}

       		   $k->save();

		}


		DB::beginTransaction();
		DB::statement("UPDATE todosabc.calificaciones_bachillerato SET estado = 'D' WHERE desertado = true AND estado IS NULL");
		DB::commit();

	DB::beginTransaction();
		DB::statement("DELETE FROM todosabc.calificaciones_bachillerato WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (partition BY id_usuario, id_matriculado, id_materia_oferta, id_oferta_fase order by id desc) AS rnum FROM todosabc.calificaciones_bachillerato ) t WHERE t.rnum > 1)");
		DB::commit();
	//}
		$this->cargarEstudiantesBachillerato($materia_oferta, $docente->id, $paralelo, $fase);

		if($request->has('descargar')){
			$pdf = PDF::loadView('calificaciones.pdfs.pdf-bachillerato', ['ie' => $this->ie,
					   'estudiantes' => $this->calificaciones_bachillerato,
					   'oferta' => $oferta,
					   'asignatura' => $asignatura,
					   'id_materia_oferta' => $materia_oferta->id,
					   'docente' => $docente,
					   'paralelo' => $paralelo,
					   'fase' => $fase,
					  ])
			->setPaper('a4', 'landscape');
			return $pdf->download('calificaciones_bachillerato.pdf');
		}else{
			view()->share(['ie' => $this->ie,
					   'estudiantes' => $this->calificaciones_bachillerato,
					   'oferta' => $oferta,
					   'asignatura' => $asignatura,
					   'id_materia_oferta' => $materia_oferta->id,
					   'docente' => $docente,
					   'paralelo' => $paralelo,
					   'fase' => $fase,
					  ]);
		}


		return view('calificaciones.bachillerato');


	}

	public function guardarBasica(Request $request){
		$this->cargarInstitucionesDelUsuario();
		$materia_oferta = MateriaOferta::find($request->input('id_materia_oferta'));
		$docente = Docente::find($request->input('id_docente'));
		$oferta = Oferta::find($materia_oferta->id_oferta);
		$asignatura = DB::table('materias')->find($materia_oferta->id_materia);
		$paralelo = $request->input('paralelo');

		$this->cargarEstudiantesBasica($materia_oferta, $docente->id, $paralelo);

		foreach ($this->calificaciones_basica as $cal) {
			$codin = $cal->codigo_inscripcion;
			$k = CalificacionBasica::find($cal->id);
			$k->fecha_registro = date('Y-m-d h:m:s');
			$k->registrado_por = session('user')->usuario;
			$k->quimestre_1_parcial_1 = $request->input('hq1p1-'.$codin);
			$k->quimestre_1_parcial_2 = $request->input('hq1p2-'.$codin);
			$k->quimestre_1_parcial_3 = $request->input('hq1p3-'.$codin);
			$k->quimestre_1_examen = $request->input('hex1-'.$codin);
			$k->quimestre_1_comportamiento = $request->input('hcompq1-'.$codin);
			$k->quimestre_1_promedio_parciales = $request->input('hq1pmpar-'.$codin);
			$k->quimestre_1_final = $request->input('hpmfq1-'.$codin);
			$k->quimestre_2_parcial_1 = $request->input('hq2p1-'.$codin);
			$k->quimestre_2_parcial_2 = $request->input('hq2p2-'.$codin);
			$k->quimestre_2_parcial_3 = $request->input('hq2p3-'.$codin);
			$k->quimestre_2_examen = $request->input('hex2-'.$codin);
			$k->quimestre_2_comportamiento = $request->input('hcompq2-'.$codin);
			$k->quimestre_2_promedio_parciales = $request->input('hq2pmpar-'.$codin);
			$k->quimestre_2_final = $request->input('hpmfq2-'.$codin);

			if($request->input('ds-'.$codin)==null){
				$k->desertado = false;
			}else{
				$k->desertado = $request->input('ds-'.$codin);
			}

    		$k->estado = $request->input('hem-'.$codin);
    		$k->nota_final = $request->input('hnf-'.$codin);
    		$k->supletorio = $request->input('hsup-'.$codin);
    		$k->remedial = $request->input('hrem-'.$codin);
    		$k->gracia = $request->input('hgrac-'.$codin);

    		if($k->gracia > 0){
    			$k->id_materia_oferta_gracia = $materia_oferta->id;
    		}else{
    			$k->id_materia_oferta_gracia = -1;
    		}

			CalificacionBasica::where('id_matriculado', $k->id_matriculado)
			->update(['id_materia_oferta_gracia' => $k->id_materia_oferta_gracia]);

			if($k->desertado==true){
				CalificacionBasica::where('id_matriculado', $k->id_matriculado)
				->update(['fecha_registro' => date('Y-m-d h:m:s'),'desertado' => true]);

			CalificacionBasica::where('estado',null)
			->update(['estado' =>'D']);

			}else{
				CalificacionBasica::where('id_matriculado', $k->id_matriculado)
				->update(['fecha_registro' => date('Y-m-d h:m:s'),'desertado' => false]);
			}

    		$k->save();
		}
		//DB::beginTransaction();

			//CalificacionBasica::where('desertado',true)
			//->where('estado',null)
			//->update(['estado' =>'D']);

		//DB::statement("UPDATE todosabc.calificaciones_basica
		//	SET estado = 'D' WHERE desertado = true AND estado IS NULL");
		//DB::commit();

		DB::beginTransaction();

		DB::statement("DELETE FROM todosabc.calificaciones_basica WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (partition BY id_usuario, id_matriculado, id_materia_oferta, id_fase order by id desc) AS rnum FROM todosabc.calificaciones_basica ) t WHERE t.rnum > 1)");
		DB::commit();

		$this->cargarEstudiantesBasica($materia_oferta, $docente->id, $paralelo);

		view()->share(['ie' => $this->ie,
					   'estudiantes' => $this->calificaciones_basica,
					   'oferta' => $oferta,
					   'asignatura' => $asignatura,
					   'id_materia_oferta' => $materia_oferta->id,
					   'docente' => $docente,
					   'paralelo' => $paralelo,
					  ]);

		if($request->has('descargar')){
			$pdf = PDF::loadView('calificaciones.pdfs.pdf-basica', ['ie' => $this->ie,
					   'estudiantes' => $this->calificaciones_basica,
					   'oferta' => $oferta,
					   'asignatura' => $asignatura,
					  ])
			->setPaper('a4', 'landscape');
			return $pdf->download('calificaciones_basica.pdf');
		}

		return view('calificaciones.basica');
	}

	public function guardarPost(Request $request){
		$this->cargarInstitucionesDelUsuario();
		$materia_oferta = MateriaOferta::find($request->input('id_materia_oferta'));
		$this->cargarEstudiantesPost($materia_oferta, $request->input('id_institucion'));
		foreach ($this->calificaciones_post as $cal) {
			$codin = $cal->codigo_inscripcion;
			$k = CalificacionPost::find($cal->id);
			$k->fecha_registro = date('Y-m-d h:m:s');
			$k->registrado_por = session('user')->usuario;
			$k->parcial_1 = $request->input('hp1-'.$codin);
    		$k->parcial_2 = $request->input('hp2-'.$codin);
    		$k->promedio_parciales = $request->input('hpm-'.$codin);
    		$k->examen = $request->input('hex-'.$codin);
    		$k->comportamiento = $request->input('hevcomp-'.$codin);
    		$k->desertado = $request->input('hds-'.$codin);
    		if($request->input('hem-'.$codin)==true){
    			$k->estado = $request->input('hem-'.$codin);
    		}
    		$k->nota_final = $request->input('hnf-'.$codin);
    		$k->save();
		}

		DB::statement("UPDATE todosabc.calificaciones_post SET estado = 'D' WHERE desertado = true AND estado IS NULL");

		DB::statement("DELETE FROM todosabc.calificaciones_post WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (partition BY id_usuario, id_matriculado, id_materia_oferta order by id desc) AS rnum FROM todosabc.calificaciones_post ) t WHERE t.rnum > 1)");

		$oferta = Oferta::find($materia_oferta->id_oferta);
		$asignatura = DB::table('materias')
		->find($materia_oferta->id_materia);

		$this->cargarEstudiantesPost($materia_oferta, $request->input('id_institucion'));

		view()->share(['ie' => $this->ie,
					   'estudiantes' => $this->calificaciones_post,
					   'oferta' => $oferta,
					   'asignatura' => $asignatura,
					   'id_materia_oferta' => $materia_oferta->id,
					  ]);

		return view('calificaciones.post');
	}

	public function guardarAlfa(Request $request){
		$this->cargarInstitucionesDelUsuario();
		$this->cargarEstudiantesAlfa();
		foreach($this->calificaciones_alfa as $cal){
			$codin = $cal->codigo_inscripcion;
			$k = CalificacionAlfa::find($request->input('id_matriculado-'.$codin));
			$k->fecha_registro = date('Y-m-d h:m:s');
			$k->registrado_por = session('user')->usuario;
			$k->modulo_1_parcial_1 = $request->input('hm1p1-'.$codin);
    		$k->modulo_1_parcial_2 = $request->input('hm1p2-'.$codin);
    		$k->modulo_1_parcial_3 = $request->input('hm1p3-'.$codin);
    		$k->modulo_1_parcial_4 = $request->input('hm1p4-'.$codin);
    		$k->modulo_1_promedio = $request->input('hpm1-'.$codin);
    		$k->modulo_1_comportamiento = $request->input('hm1evcomp-'.$codin);
    		$k->modulo_2_parcial_1 = $request->input('hm2p1-'.$codin);
    		$k->modulo_2_parcial_2 = $request->input('hm2p2-'.$codin);
    		$k->modulo_2_parcial_3 = $request->input('hm2p3-'.$codin);
    		$k->modulo_2_parcial_4 = $request->input('hm2p4-'.$codin);
    		$k->modulo_2_promedio = $request->input('hpm2-'.$codin);
    		$k->modulo_2_comportamiento = $request->input('hm2evcomp-'.$codin);
    		$k->nota_final = $request->input('hnf-'.$codin);

    		if($k->nota_final >= 7){
    			$k->modulo_estado = 'P';
    		}else if($k->nota_final < 7){
    			$k->modulo_estado = 'NP';
    		}

    		if($request->input('hdm1-'.$codin)==true || $request->input('hdm2-'.$codin)==true){
    			$k->modulo_1_desertado = true;
    			$k->modulo_2_desertado = true;
    			$k->modulo_estado = 'D';
    		}

    		$k->save();

    		if($k->modulo_estado!='D'){
    			$mat = DB::table('todosabc.matriculados')
    			->where('codigo_inscripcion', $codin)
    			->update(['razon_desercion' => null]);
    		}
		}

		DB::statement("UPDATE todosabc.calificaciones_alfa SET modulo_estado = 'D' WHERE (modulo_1_desertado = true OR modulo_2_desertado = true) AND modulo_estado IS NULL");

		DB::statement("DELETE FROM todosabc.calificaciones_alfa WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (partition BY id_usuario, id_matriculado order by id desc) AS rnum FROM todosabc.calificaciones_alfa ) t WHERE t.rnum > 1)");

		$this->cargarEstudiantesAlfa();
		return view('calificaciones.alfa',['ie' => $this->ie,
										   'estudiantes' => $this->calificaciones_alfa,
										  ]);
	}

	public function alfa(Request $request){
		DB::statement("UPDATE todosabc.calificaciones_alfa SET modulo_estado = 'D' WHERE (modulo_1_desertado = true OR modulo_2_desertado = true) AND modulo_estado IS NULL");

		DB::statement("DELETE FROM todosabc.calificaciones_alfa WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (partition BY id_usuario, id_matriculado order by id desc) AS rnum FROM todosabc.calificaciones_alfa ) t WHERE t.rnum > 1)");

		$this->cargarInstitucionesDelUsuario();
		$this->cargarEstudiantesAlfa();
		view()->share(['ie' => $this->ie,
					   'estudiantes' => $this->calificaciones_alfa,
					   ]);

		if($request->has('descargar')){
			$pdf = PDF::loadView('calificaciones.pdfs.pdf-alfa', ['ie' => $this->ie,
																  'estudiantes' => $this->calificaciones_alfa,
																  ])
			->setPaper('a4', 'landscape');
			return $pdf->download('calificaciones_alfa.pdf');
		}

		return view('calificaciones.alfa');
	}

	private function cargarInstitucionesDelUsuario(){
    	$this->ie = DB::table('todosabc.instituciones')
    	->join('codigos_amie','codigos_amie.amie','=','todosabc.instituciones.amie')
    	->select('todosabc.instituciones.id','codigos_amie.zona','codigos_amie.provincia','codigos_amie.canton','codigos_amie.distrito','codigos_amie.amie','codigos_amie.institucion')
    	->where('id_usuario', session('user')->id)
    	->first();
    }

    private function cargarEstudiantesAlfa(){
		$docente = DB::table('todosabc.docentes')
    	->where('id_institucion',$this->ie->id)
    	->first();

		$estudiantes = DB::table('todosabc.matriculados')
    	->where('id_docente', $docente->id)
    	->where('id_oferta', session('user')->id_oferta)
    	->where('asiste_con_frecuencia', true)
    	->orderBy('nombres_aspirantes')
    	->get();

    	foreach ($estudiantes as $estudiante) {
    		$k_existente = DB::table('todosabc.calificaciones_alfa')
    		->where('id_matriculado',$estudiante->id)
    		->where('id_usuario',session('user')->id)
    		->first();

    		if($k_existente==null){
	    		$k = new CalificacionAlfa;
	    		$k->id_matriculado = $estudiante->id;
	    		$k->id_usuario = session('user')->id;
	    		$k->fecha_registro = date('Y-m-d h:m:s');
	    		$k->registrado_por = 'SISTEMA';
	    		$k->modulo_1_parcial_1 = 0;
	    		$k->modulo_1_parcial_2 = 0;
	    		$k->modulo_1_parcial_3 = 0;
	    		$k->modulo_1_parcial_4 = 0;
	    		$k->modulo_1_desertado = false;
	    		$k->modulo_2_parcial_1 = 0;
	    		$k->modulo_2_parcial_2 = 0;
	    		$k->modulo_2_parcial_3 = 0;
	    		$k->modulo_2_parcial_4 = 0;
	    		$k->modulo_2_desertado = false;
	    		$k->save();
    		}
    	}

    	$this->cargarCalificacionesAlfa();
    }

    private function cargarEstudiantesBachillerato(MateriaOferta $mo, $id_docente, $paralelo, $fase){
    	if(substr($fase->nombre, 0 , 1)=='1'){
    		$estudiantes = DB::table('todosabc.matriculados')
	    	->where('id_institucion', $this->ie->id)
	    	->where('id_oferta', session('user')->id_oferta)
	    	->where('paralelo', $paralelo)
	    	->where('fase1', true)
	    	->where('asiste_con_frecuencia', true)
	    	->orderBy('nombres_aspirantes')
	    	->get();
    	}else if(substr($fase->nombre, 0 , 1)=='2'){
    		$estudiantes = DB::table('todosabc.matriculados')
	    	->where('id_institucion', $this->ie->id)
	    	->where('id_oferta', session('user')->id_oferta)
	    	->where('paralelo', $paralelo)
	    	->where('fase2', true)
	    	->where('asiste_con_frecuencia', true)
	    	->orderBy('nombres_aspirantes')
	    	->get();
    	}else if(substr($fase->nombre, 0 , 1)=='3'){
    		$estudiantes = DB::table('todosabc.matriculados')
	    	->where('id_institucion', $this->ie->id)
	    	->where('id_oferta', session('user')->id_oferta)
	    	->where('paralelo', $paralelo)
	    	->where('fase3', true)
	    	->where('asiste_con_frecuencia', true)
	    	->orderBy('nombres_aspirantes')
	    	->get();
    	}

    	foreach ($estudiantes as $estudiante) {
    		$k_existente = DB::table('todosabc.calificaciones_bachillerato')
    		->where('id_matriculado',$estudiante->id)
    		->where('id_usuario',session('user')->id)
    		->where('id_materia_oferta',$mo->id)
    		->where('id_oferta_fase', $fase->id)
    		->first();

    		if($k_existente==null){
	    		$k = new CalificacionBachillerato;
	    		$k->id_matriculado = $estudiante->id;
	    		$k->id_usuario = session('user')->id;
	    		$k->id_materia_oferta = $mo->id;
	    		$k->id_oferta_fase = $fase->id;
	    		$k->fecha_registro = date('Y-m-d h:m:s');
	    		$k->registrado_por = 'SISTEMA';
	    		$k->quimestre_1_parcial_1 = 0;
				$k->quimestre_1_parcial_2 = 0;
				$k->quimestre_1_examen = 0;
				$k->quimestre_2_parcial_1 = 0;
				$k->quimestre_2_parcial_2 = 0;
				$k->quimestre_2_examen = 0;
				$k->id_docente = $id_docente;

				$desertados = CalificacionBachillerato::where('id_matriculado', $estudiante->id)
				->where('id_oferta_fase', $fase->id)
				->where('desertado', true)
				->orderby('id_matriculado','desc')
				->get();

				if($desertados->count() > 0){
					$k->desertado = true;
					$k->id_materia_oferta_gracia = $desertados[0]->id_materia_oferta_gracia;
				}else{
					$k->desertado = false;
					$k->id_materia_oferta_gracia = -1;
				}

				$gracias = CalificacionBachillerato::where('id_matriculado', $estudiante->id)
				->where('id_oferta_fase', $fase->id)
				->where('gracia', '>', 0)
				->orderby('id_matriculado','desc')
				->get();

				if($gracias->count() == 0){
					$k->id_materia_oferta_gracia = 1;
				}

				$k->supletorio = 0;
				$k->remedial = 0;
				$k->gracia = 0;
				$k->nota_final = 0;
	    		$k->save();
    		}
    	}

    	$this->cargarCalificacionesBachillerato($mo, $id_docente, $paralelo, $fase);
    }

    private function cargarEstudiantesBasica(MateriaOferta $mo, $id_docente, $paralelo){
		$estudiantes = DB::table('todosabc.matriculados')
    	->where('id_institucion', $this->ie->id)
    	->where('id_oferta', session('user')->id_oferta)
    	->where('paralelo', $paralelo)
    	->where('asiste_con_frecuencia', true)
    	->orderBy('nombres_aspirantes')
    	->get();

    	foreach ($estudiantes as $estudiante) {
    		$k_existente = DB::table('todosabc.calificaciones_basica')
    		->where('id_matriculado',$estudiante->id)
    		->where('id_usuario',session('user')->id)
    		->where('id_materia_oferta',$mo->id)
    		->first();

    		if($k_existente==null){
	    		$k = new CalificacionBasica;
	    		$k->id_matriculado = $estudiante->id;
	    		$k->id_usuario = session('user')->id;
	    		$k->id_materia_oferta = $mo->id;
	    		$k->fecha_registro = date('Y-m-d h:m:s');
	    		$k->registrado_por = 'SISTEMA';
	    		$k->quimestre_1_parcial_1 = 0;
				$k->quimestre_1_parcial_2 = 0;
				$k->quimestre_1_parcial_3 = 0;
				$k->quimestre_1_examen = 0;
				$k->quimestre_2_parcial_1 = 0;
				$k->quimestre_2_parcial_2 = 0;
				$k->quimestre_2_parcial_3 = 0;
				$k->quimestre_2_examen = 0;
				$k->id_fase = 1;
				$k->id_docente = $id_docente;

				$desertados = CalificacionBasica::where('id_matriculado', $estudiante->id)
				->where('desertado', true)
				->get();

				if($desertados->count() > 0){
					$k->desertado = true;
					$k->id_materia_oferta_gracia = $desertados[0]->id_materia_oferta_gracia;
				}else{
					$k->desertado = false;
					$k->id_materia_oferta_gracia = -1;
				}

				$gracias = CalificacionBasica::where('id_matriculado', $estudiante->id)
				->where('gracia', '>', 0)
				->get();



				if($gracias->count() == 0){
					$k->id_materia_oferta_gracia = 1;
				}

				$k->supletorio = 0;
				$k->remedial = 0;
				$k->gracia = 0;
				$k->nota_final = 0;
	    		$k->save();
    		}
    	}

    	$this->cargarCalificacionesBasica($mo, $id_docente, $paralelo);
    }

    private function cargarEstudiantesPost(MateriaOferta $mo, $id_institucion){
		$estudiantes = DB::table('todosabc.matriculados')
    	->where('id_oferta', $mo->id_oferta)
    	->where('id_institucion', $id_institucion)
    	->where('todosabc.matriculados.asiste_con_frecuencia', true)
    	->orderBy('nombres_aspirantes')
    	->get();

    	foreach ($estudiantes as $estudiante) {
    		$k_existente = DB::table('todosabc.calificaciones_post')
    		->where('id_matriculado',$estudiante->id)
    		->where('id_usuario',session('user')->id)
    		->where('id_materia_oferta',$mo->id)
    		->first();

    		if($k_existente==null){
	    		$k = new CalificacionPost;
	    		$k->id_matriculado = $estudiante->id;
	    		$k->id_usuario = session('user')->id;
	    		$k->id_materia_oferta = $mo->id;
	    		$k->fecha_registro = date('Y-m-d h:m:s');
	    		$k->parcial_1 = 0;
	    		$k->parcial_2 = 0;
	    		$k->promedio_parciales = 0;
	    		$k->examen = 0;
	    		$k->desertado = false;
	    		$k->registrado_por = 'SISTEMA';
	    		$k->save();
    		}
    	}

	    $this->cargarCalificacionesPost($mo, $id_institucion);
    }

    private function cargarCalificacionesAlfa(){
    	$this->calificaciones_alfa = DB::table('todosabc.calificaciones_alfa')
    	->join('todosabc.matriculados','todosabc.calificaciones_alfa.id_matriculado','=','todosabc.matriculados.id')
    	->select('todosabc.matriculados.nombres_aspirantes','todosabc.matriculados.cedula_identidad','todosabc.matriculados.codigo_inscripcion','todosabc.calificaciones_alfa.*')
    	->where('todosabc.calificaciones_alfa.id_usuario',session('user')->id)
    	->where('todosabc.matriculados.asiste_con_frecuencia', true)
    	->orderBy('todosabc.matriculados.nombres_aspirantes')
    	->get();
    }

    private function cargarCalificacionesPost(MateriaOferta $mo, $id_institucion){
    	$this->calificaciones_post = DB::table('todosabc.calificaciones_post')
    	->join('todosabc.matriculados','todosabc.calificaciones_post.id_matriculado','=','todosabc.matriculados.id')
    	->select('todosabc.matriculados.nombres_aspirantes','todosabc.matriculados.cedula_identidad','todosabc.matriculados.codigo_inscripcion','todosabc.calificaciones_post.*')
    	->where('todosabc.matriculados.id_oferta',$mo->id_oferta)
    	->where('todosabc.calificaciones_post.id_usuario',session('user')->id)
    	->where('todosabc.calificaciones_post.id_materia_oferta', $mo->id)
    	->where('todosabc.matriculados.id_institucion', $id_institucion)
    	->where('todosabc.matriculados.asiste_con_frecuencia', true)
    	->orderBy('todosabc.matriculados.nombres_aspirantes')
    	->get();
    }

    private function cargarCalificacionesBachillerato(MateriaOferta $mo, $id_docente, $paralelo, $fase){
		$this->calificaciones_bachillerato = DB::table('todosabc.calificaciones_bachillerato')
    	->leftJoin('todosabc.matriculados','todosabc.calificaciones_bachillerato.id_matriculado','=','todosabc.matriculados.id')
    	->select('todosabc.matriculados.nombres_aspirantes','todosabc.matriculados.codigo_inscripcion','todosabc.matriculados.cedula_identidad','todosabc.calificaciones_bachillerato.*')
    	->where('todosabc.calificaciones_bachillerato.id_usuario',session('user')->id)
    	->where('todosabc.matriculados.id_oferta', $mo->id_oferta)
    	->where('todosabc.calificaciones_bachillerato.id_materia_oferta', $mo->id)
    	->where('todosabc.matriculados.id_institucion', $this->ie->id)
    	->where('todosabc.calificaciones_bachillerato.id_docente', $id_docente)
    	->where('todosabc.matriculados.paralelo', $paralelo)
    	->where('todosabc.calificaciones_bachillerato.id_oferta_fase', $fase->id)
    	->orderBy('todosabc.matriculados.nombres_aspirantes')
    	->get();
    }

    private function cargarCalificacionesBasica(MateriaOferta $mo, $id_docente, $paralelo){
		$this->calificaciones_basica = DB::table('todosabc.calificaciones_basica')
    	->leftJoin('todosabc.matriculados','todosabc.calificaciones_basica.id_matriculado','=','todosabc.matriculados.id')
    	->select('todosabc.matriculados.nombres_aspirantes','todosabc.matriculados.codigo_inscripcion','todosabc.matriculados.cedula_identidad','todosabc.calificaciones_basica.*')
    	->where('todosabc.calificaciones_basica.id_usuario',session('user')->id)
    	->where('todosabc.matriculados.id_oferta', $mo->id_oferta)
    	->where('todosabc.calificaciones_basica.id_materia_oferta', $mo->id)
    	->where('todosabc.matriculados.id_institucion', $this->ie->id)
    	->where('todosabc.calificaciones_basica.id_docente', $id_docente)
    	->where('todosabc.matriculados.paralelo', $paralelo)
    	->orderBy('todosabc.matriculados.nombres_aspirantes')
    	->get();
    }

    private function cargarDocentesUsuario(){
    	$this->docentes = DB::table('todosabc.docentes')
    	->where('id_institucion', $this->ie->id)
    	->orderBy('apellidos')
    	->get();
    }

    private function cargarParalelosEstudiantes(){
    	$this->paralelos = DB::table('todosabc.matriculados')
    	->select('paralelo')
    	->where('id_institucion', $this->ie->id)
    	->where('asiste_con_frecuencia', true)
    	->groupBy('paralelo')
    	->orderBy('paralelo')
    	->get();
    }

    private function cargarDocentesAsignaturas(){
    	if(session('user')->id_oferta==6 or session('user')->id_oferta==13){
    		$this->docentes_asignaturas = DB::select("select ca.institucion, m.paralelo, (d.apellidos || ' ' || d.nombres) as docente, ma.nombre as asignatura from todosabc.usuarios u, todosabc.instituciones ie, todosabc.docentes d, codigos_amie ca, todosabc.matriculados m, todosabc.materias_oferta mo, materias ma,
    		todosabc.calificaciones_basica b where u.id = ie.id_usuario and ie.id = d.id_institucion and ie.amie = ca.amie and
			ie.id = m.id_institucion and mo.id_oferta = u.id_oferta and mo.id_materia = ma.id and m.id = b.id_matriculado and d.id = b.id_docente and mo.id = b.id_materia_oferta and u.id = :idus group by ca.institucion, m.paralelo, d.apellidos, d.nombres, ma.nombre order by ca.institucion, m.paralelo, docente, asignatura;", ['idus' => session('user')->id]);
    	}else if(session('user')->id_oferta==7 or session('user')->id_oferta==14){
   			$this->docentes_asignaturas = DB::select("select f.nombre as nivel, ca.institucion, m.paralelo, (d.apellidos || ' ' || d.nombres) as docente, ma.nombre as asignatura from todosabc.usuarios u, todosabc.instituciones ie, todosabc.docentes d, codigos_amie ca, todosabc.matriculados m, todosabc.materias_oferta mo, materias ma, todosabc.calificaciones_bachillerato b, todosabc.fases_oferta f where u.id = ie.id_usuario and ie.id = d.id_institucion and ie.id = m.id_institucion and ie.amie = ca.amie and mo.id_materia = ma.id and mo.id_oferta = u.id_oferta and f.id_oferta = u.id_oferta and b.id_docente = d.id and b.id_oferta_fase = f.id and b.id_materia_oferta = mo.id and b.id_docente = d.id and b.id_matriculado = m.id and u.id = :idus group by f.nombre, ca.institucion, m.paralelo, d.apellidos, d.nombres, ma.nombre order by nivel, ca.institucion, m.paralelo, docente, asignatura;", ['idus' => session('user')->id]);
    	}
    }

}
