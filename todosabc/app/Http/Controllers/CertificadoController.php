<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Storage;
use Dompdf\Dompdf;
use Dompdf\Options;
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;
use PDF;
use App\Modelo\Matriculado;
use App\Modelo\Oferta;
use App\Modelo\Institucion;
use App\Modelo\FaseOferta;

class CertificadoController extends Controller
{
	private $calificaciones = null;
	private $calificaciones_fase_1 = null;
	private $calificaciones_fase_2 = null;
	private $calificaciones_fase_3 = null;
	private $desertados;

	public function certificarPromocion(Request $request){
		$matriculado = Matriculado::find($request->input('id_mat'));
		$oferta = Oferta::find($matriculado->id_oferta);

		$ca = DB::table('codigos_amie')
		->where('amie',$request->input('amie'))
		->first();

		if($matriculado->id_oferta==2 || $matriculado->id_oferta==3 || $matriculado->id_oferta==4 || $matriculado->id_oferta==5 || $matriculado->id_oferta==6 || $matriculado->id_oferta==7 ){
			$codigo = '2017-'.$matriculado->id_oferta.'-'.$ca->canton.'-'.$ca->amie.'-'.$matriculado->codigo_inscripcion;
		}else if($matriculado->id_oferta==10 || $matriculado->id_oferta==11 || $matriculado->id_oferta==12 || $matriculado->id_oferta==13 || $matriculado->id_oferta==14){
			$codigo = '2018-'.$matriculado->id_oferta.'-'.$ca->canton.'-'.$ca->amie.'-'.$matriculado->codigo_inscripcion;
		}else{
			$codigo = '2019-'.$matriculado->id_oferta.'-'.$ca->canton.'-'.$ca->amie.'-'.$matriculado->codigo_inscripcion;
		}
		
		$barra = DNS1D::getBarcodeHTML($codigo,'C93',2.5,45);

		$distrito = $ca->canton.', '.$this->obtenerMes().' / '.date('Y');
		$ie_nombre = $ca->institucion;

		if($matriculado->id_oferta==2 || $matriculado->id_oferta==10){
			$this->calificaciones = DB::select("select cp.nota_final, cp.modulo_2_comportamiento, m.nombres_aspirantes from todosabc.calificaciones_alfa cp, todosabc.matriculados m where m.id = cp.id_matriculado and cp.id_matriculado = :mid and cp.id_usuario = :uid", ['mid' => $matriculado->id, 'uid' => session('user')->id]);
		}else if($matriculado->id_oferta==6 || $matriculado->id_oferta==13){
			$this->calificaciones = DB::select("select cb.nota_final, cb.quimestre_2_comportamiento as comportamiento, m.nombre as asignatura from todosabc.calificaciones_basica cb, todosabc.materias_oferta mo, materias m where mo.id_materia = m.id and cb.id_materia_oferta = mo.id and cb.id_matriculado = :mid and mo.id_oferta = :oid and cb.id_usuario = :uid order by m.nombre", ['oid' => $matriculado->id_oferta, 'mid' => $matriculado->id, 'uid' => session('user')->id]);			
		}else if($matriculado->id_oferta==7 || $matriculado->id_oferta==14){
			$fases_oferta = DB::table('todosabc.fases_oferta')
			->where('id_oferta', $matriculado->id_oferta)
			->orderBy('id')
			->get();

			if($matriculado->fase1==true){
				$this->calificaciones_fase_1 = DB::select("select cb.nota_final, cb.quimestre_2_comportamiento as comportamiento, m.nombre as asignatura from todosabc.calificaciones_bachillerato cb, todosabc.materias_oferta mo, materias m where mo.id_materia = m.id and cb.id_materia_oferta = mo.id and cb.id_matriculado = :mid and mo.id_oferta = :oid and cb.id_usuario = :uid and cb.id_oferta_fase = :idof order by m.nombre", ['oid' => $matriculado->id_oferta, 'mid' => $matriculado->id, 'uid' => session('user')->id, 'idof' => $fases_oferta[0]->id]);
			}

			if($matriculado->fase2==true){
				$this->calificaciones_fase_2 = DB::select("select cb.nota_final, cb.quimestre_2_comportamiento as comportamiento, m.nombre as asignatura from todosabc.calificaciones_bachillerato cb, todosabc.materias_oferta mo, materias m where mo.id_materia = m.id and cb.id_materia_oferta = mo.id and cb.id_matriculado = :mid and mo.id_oferta = :oid and cb.id_usuario = :uid and cb.id_oferta_fase = :idof order by m.nombre", ['oid' => $matriculado->id_oferta, 'mid' => $matriculado->id, 'uid' => session('user')->id, 'idof' => $fases_oferta[1]->id]);
			}

			if($matriculado->fase3==true){
				$this->calificaciones_fase_3 = DB::select("select cb.nota_final, cb.quimestre_2_comportamiento as comportamiento, m.nombre as asignatura from todosabc.calificaciones_bachillerato cb, todosabc.materias_oferta mo, materias m where mo.id_materia = m.id and cb.id_materia_oferta = mo.id and cb.id_matriculado = :mid and mo.id_oferta = :oid and cb.id_usuario = :uid and cb.id_oferta_fase = :idof order by m.nombre", ['oid' => $matriculado->id_oferta, 'mid' => $matriculado->id, 'uid' => session('user')->id, 'idof' => $fases_oferta[2]->id]);
			}
		}else {
			$this->calificaciones = DB::select("select cp.nota_final, cp.comportamiento, m.nombre as asignatura from todosabc.calificaciones_post cp, todosabc.materias_oferta mo, materias m where cp.id_materia_oferta = mo.id and mo.id_materia = m.id and mo.id_oferta = :oid and cp.id_matriculado = :mid and cp.id_usuario = :uid order by m.nombre", ['oid' => $matriculado->id_oferta, 'mid' => $matriculado->id, 'uid' => session('user')->id]);
		}

		if($matriculado->id_oferta==2 || $matriculado->id_oferta==10){
			$pdf = PDF::loadView('certificados.alfa-promocion',['barra' => $barra,
													 'codigo' => $codigo,
													 'distrito' => $distrito,
													 'ie_nombre' => $ie_nombre,
													 'oferta' => $oferta,
													 'matriculado' => $matriculado,
													 'calificaciones' => $this->calificaciones,
													])
			->setPaper('a4', 'portrait');
		}else if($matriculado->id_oferta==6 || $matriculado->id_oferta==13){
			$pdf = PDF::loadView('certificados.basica-promocion',['barra' => $barra,
													 'codigo' => $codigo,
													 'distrito' => $distrito,
													 'ie_nombre' => $ie_nombre,
													 'oferta' => $oferta,
													 'matriculado' => $matriculado,
													 'calificaciones' => $this->calificaciones,
													])
			->setPaper('a4', 'portrait');
		}else if($matriculado->id_oferta==7 || $matriculado->id_oferta==14){
			$fases_oferta = DB::table('todosabc.fases_oferta')
			->where('id_oferta', $matriculado->id_oferta)
			->orderBy('id')
			->get();

			$pdf = PDF::loadView('certificados.bachillerato-promocion',['barra' => $barra,
													 'codigo' => $codigo,
													 'distrito' => $distrito,
													 'ie_nombre' => $ie_nombre,
													 'oferta' => $oferta,
													 'matriculado' => $matriculado,
													 'fases_oferta' => $fases_oferta,
													 'calificaciones_fase_1' => $this->calificaciones_fase_1,
													 'calificaciones_fase_2' => $this->calificaciones_fase_2,
													 'calificaciones_fase_3' => $this->calificaciones_fase_3,
													])
			->setPaper('a4', 'portrait');
		}else{
			$pdf = PDF::loadView('certificados.post-promocion',['barra' => $barra,
													 'codigo' => $codigo,
													 'distrito' => $distrito,
													 'ie_nombre' => $ie_nombre,
													 'oferta' => $oferta,
													 'matriculado' => $matriculado,
													 'calificaciones' => $this->calificaciones,
													])
			->setPaper('a4', 'portrait');
		}

		return $pdf->download('certificado_promocion_'.str_replace(" ", "_",(strtolower($matriculado->nombres_aspirantes)).'.pdf'));
	}

	public function borrarMensaje(Request $request){
		$request->session()->put('estadoK', 200);
		return redirect()->route('calificaciones_alfa');
	}
	
	public function verificar(Request $request){
		//verificar desertados
		if($this->verificar_desertados() > 0){
			return redirect()->route('matriculados_desertados');
		}

		//verificar calificaciones		
		if($this->verificar_calificaciones() > 0){
			$request->session()->put('estadoK', 400);
			if(session('user')->id_oferta==8 || session('user')->id_oferta==15){
				return redirect()->route('calificaciones_prepost');
			}else if(session('user')->id_oferta==6 || session('user')->id_oferta==13){
				return redirect()->route('calificaciones_prebasica',['token' => session('token')]);
			}else if(session('user')->id_oferta==7 || session('user')->id_oferta==14){
				return redirect()->route('matriculados_bachillerato');
			}			
		}else{
			$request->session()->put('estadoK', 200);
		}


		if(session('user')->id_oferta==2 || session('user')->id_oferta==8 || session('user')->id_oferta==10 || session('user')->id_oferta==15){
			$matriculado = DB::select('select m.id, m.id_oferta, m.nombres_aspirantes, m.cedula_identidad, m.fase1, m.fase2, m.fase3, m.paralelo, ca.institucion, ca.amie, oe.nombre as oferta from todosabc.matriculados m, todosabc.docentes d, todosabc.instituciones ie, codigos_amie ca, todosabc.ofertas oe where m.id_oferta = oe.id and d.id_institucion = ie.id and d.id = m.id_docente and ie.amie = ca.amie and m.id = :mid', ['mid' => $request->input('id_mat')]);
		}else{
			$matriculado = DB::select('select m.id, m.id_oferta, m.nombres_aspirantes, m.cedula_identidad, m.fase1, m.fase2, m.fase3, m.paralelo, ca.institucion, ca.amie, oe.nombre as oferta from todosabc.matriculados m, todosabc.instituciones ie, codigos_amie ca, todosabc.ofertas oe where m.id_oferta = oe.id and m.id_institucion = ie.id and ie.amie = ca.amie and m.id = :mid', ['mid' => $request->input('id_mat')]);
		}

		$asignaturas = DB::table('todosabc.materias_oferta')
		->where('id_oferta', $matriculado[0]->id_oferta)
		->orderBy('id_materia')
		->get();

		$ok = 1;//todo bien
		$asignaturas_no_aprobadas = '';

		if($matriculado[0]->id_oferta==2 || $matriculado[0]->id_oferta==10){
			$calificacion = DB::table('todosabc.calificaciones_alfa')
			->where('id_matriculado', $matriculado[0]->id)
			->first();

			if(isset($calificacion)){
				if($calificacion->modulo_estado!='P'){
					$ok = 3;
				}
			}

			view()->share(['matriculado' => $matriculado, 
						   'ok' => $ok,
						   ]);

		}else if($matriculado[0]->id_oferta==6 || $matriculado[0]->id_oferta==13){
			$total_calificaciones = 0;
			for($i = 0; $i < $asignaturas->count(); $i++){
				$calificacion = DB::table('todosabc.calificaciones_basica')
				->where('id_materia_oferta', $asignaturas[$i]->id)
				->where('id_usuario', session('user')->id)
				->where('id_matriculado', $matriculado[0]->id)
				->first();

				if(isset($calificacion)){
					if($calificacion->estado!='P'){
						$materia = DB::table('materias')
						->where('id', $asignaturas[$i]->id_materia)
						->first();
						$asignaturas_no_aprobadas .= $materia->nombre.', ';
					}else{
						$total_calificaciones++;
					}
				}
			}

			if($asignaturas_no_aprobadas!=''){
				$ok = 0;//materias no aprobadas
			}else if($total_calificaciones < $asignaturas->count()){
				$ok = 2;//faltan calificaciones
			}

			view()->share(['matriculado' => $matriculado, 
						   'ok' => $ok,
						   'asignaturas_no_aprobadas' => substr($asignaturas_no_aprobadas,0,strlen($asignaturas_no_aprobadas)-2),
						   ]);
		}else if($matriculado[0]->id_oferta==7 || $matriculado[0]->id_oferta==14){
			$total_calificaciones = 0;
			$total_asignaturas = 0;

			if($matriculado[0]->fase1==true){
				for($i = 0; $i < $asignaturas->count(); $i++){
					if($matriculado[0]->id_oferta==7){
						$calificaciones = DB::table('todosabc.calificaciones_bachillerato')
						->where('id_materia_oferta', $asignaturas[$i]->id)
						->where('id_usuario', session('user')->id)
						->where('id_oferta_fase', 2)				
						->where('id_matriculado', $matriculado[0]->id)
						->get();

						if($calificaciones->count() > 0){
							foreach($calificaciones as $k){
								$total_asignaturas++;
								if($k->estado!='P'){
									$fase = FaseOferta::find(2);
									$materia = DB::table('materias')
									->where('id', $asignaturas[$i]->id_materia)
									->first();
									$asignaturas_no_aprobadas .= $materia->nombre.'[fase: '.$fase->nombre.'], ';
								}else{
									$total_calificaciones++;
								}
							}
						}
					}else if($matriculado[0]->id_oferta==14){
						$calificaciones = DB::table('todosabc.calificaciones_bachillerato')
						->where('id_materia_oferta', $asignaturas[$i]->id)
						->where('id_usuario', session('user')->id)
						->where('id_oferta_fase', 6)				
						->where('id_matriculado', $matriculado[0]->id)
						->get();

						if($calificaciones->count() > 0){
							foreach($calificaciones as $k){
								$total_asignaturas++;
								if($k->estado!='P'){
									$fase = FaseOferta::find(6);
									$materia = DB::table('materias')
									->where('id', $asignaturas[$i]->id_materia)
									->first();
									$asignaturas_no_aprobadas .= $materia->nombre.'[fase: '.$fase->nombre.'], ';
								}else{
									$total_calificaciones++;
								}
							}
						}
					}
				}
			}

			if($matriculado[0]->fase2==true){
				for($i = 0; $i < $asignaturas->count(); $i++){
					if($matriculado[0]->id_oferta==7){
						$calificaciones = DB::table('todosabc.calificaciones_bachillerato')
						->where('id_materia_oferta', $asignaturas[$i]->id)
						->where('id_usuario', session('user')->id)
						->where('id_oferta_fase', 3)				
						->where('id_matriculado', $matriculado[0]->id)
						->get();

						if($calificaciones->count() > 0){
							foreach($calificaciones as $k){
								$total_asignaturas++;
								if($k->estado!='P'){
									$fase = FaseOferta::find(3);
									$materia = DB::table('materias')
									->where('id', $asignaturas[$i]->id_materia)
									->first();
									$asignaturas_no_aprobadas .= $materia->nombre.'[fase: '.$fase->nombre.'], ';
								}else{
									$total_calificaciones++;
								}
							}
						}
					}else if($matriculado[0]->id_oferta==14){
						$calificaciones = DB::table('todosabc.calificaciones_bachillerato')
						->where('id_materia_oferta', $asignaturas[$i]->id)
						->where('id_usuario', session('user')->id)
						->where('id_oferta_fase', 7)				
						->where('id_matriculado', $matriculado[0]->id)
						->get();

						if($calificaciones->count() > 0){
							foreach($calificaciones as $k){
								$total_asignaturas++;
								if($k->estado!='P'){
									$fase = FaseOferta::find(7);
									$materia = DB::table('materias')
									->where('id', $asignaturas[$i]->id_materia)
									->first();
									$asignaturas_no_aprobadas .= $materia->nombre.'[fase: '.$fase->nombre.'], ';
								}else{
									$total_calificaciones++;
								}
							}
						}
					}
				}
			}

			if($matriculado[0]->fase3==true){
				for($i = 0; $i < $asignaturas->count(); $i++){
					if($matriculado[0]->id_oferta==7){
						$calificaciones = DB::table('todosabc.calificaciones_bachillerato')
						->where('id_materia_oferta', $asignaturas[$i]->id)
						->where('id_usuario', session('user')->id)
						->where('id_oferta_fase', 4)				
						->where('id_matriculado', $matriculado[0]->id)
						->get();

						if($calificaciones->count() > 0){
							foreach($calificaciones as $k){
								$total_asignaturas++;
								if($k->estado!='P'){
									$fase = FaseOferta::find(4);
									$materia = DB::table('materias')
									->where('id', $asignaturas[$i]->id_materia)
									->first();
									$asignaturas_no_aprobadas .= $materia->nombre.'[fase: '.$fase->nombre.'], ';
								}else{
									$total_calificaciones++;
								}
							}
						}
					}else if($matriculado[0]->id_oferta==14){
						$calificaciones = DB::table('todosabc.calificaciones_bachillerato')
						->where('id_materia_oferta', $asignaturas[$i]->id)
						->where('id_usuario', session('user')->id)
						->where('id_oferta_fase', 8)				
						->where('id_matriculado', $matriculado[0]->id)
						->get();

						if($calificaciones->count() > 0){
							foreach($calificaciones as $k){
								$total_asignaturas++;
								if($k->estado!='P'){
									$fase = FaseOferta::find(8);
									$materia = DB::table('materias')
									->where('id', $asignaturas[$i]->id_materia)
									->first();
									$asignaturas_no_aprobadas .= $materia->nombre.'[fase: '.$fase->nombre.'], ';
								}else{
									$total_calificaciones++;
								}
							}
						}
					}
				}
			}

			if($asignaturas_no_aprobadas!=''){
				$ok = 0;//materias no aprobadas
			}else if($total_calificaciones < $total_asignaturas){
				$ok = 2;//faltan calificaciones
			}

			view()->share(['matriculado' => $matriculado, 
						   'ok' => $ok,
						   'asignaturas_no_aprobadas' => substr($asignaturas_no_aprobadas,0,strlen($asignaturas_no_aprobadas)-2),
						   ]);
		}else if($matriculado[0]->id_oferta==3 || $matriculado[0]->id_oferta==4 || $matriculado[0]->id_oferta==5 || $matriculado[0]->id_oferta==11 || $matriculado[0]->id_oferta==12){

			$total_calificaciones = 0;
			for($i = 0; $i < $asignaturas->count(); $i++){
				$calificacion = DB::table('todosabc.calificaciones_post')
				->where('id_materia_oferta', $asignaturas[$i]->id)
				->where('id_usuario', session('user')->id)
				->where('id_matriculado', $matriculado[0]->id)
				->first();

				if(isset($calificacion)){
					if($calificacion->estado!='P'){
						$materia = DB::table('materias')
						->where('id', $asignaturas[$i]->id_materia)
						->first();
						$asignaturas_no_aprobadas .= $materia->nombre.', ';
					}else{
						$total_calificaciones++;
					}
				}
			}

			if($asignaturas_no_aprobadas!=''){
				$ok = 0;//materias no aprobadas
			}else if($total_calificaciones < $asignaturas->count()){
				$ok = 2;//faltan calificaciones
			}

			view()->share(['matriculado' => $matriculado, 
						   'ok' => $ok,
						   'asignaturas_no_aprobadas' => substr($asignaturas_no_aprobadas,0,strlen($asignaturas_no_aprobadas)-2),
						   ]);
		}else{
			view()->share(['matriculado' => $matriculado]);
		}

		return view('certificados.resultado');
	}

	private function obtenerMes(){
		switch (date('m')) {
			case '02':
				$mes = 'Febrero';
				break;	
			case '03':
				$mes = 'Marzo';
				break;
			case '04':
				$mes = 'Abril';
				break;
			case '05':
				$mes = 'Mayo';
				break;
			case '06':
				$mes = 'Junio';
				break;
			case '07':
				$mes = 'Julio';
				break;		
			case '08':
				$mes = 'Agosto';
				break;
			case '09':
				$mes = 'Septiembre';
				break;
			case '10':
				$mes = 'Octubre';
				break;
			case '11':
				$mes = 'Noviembre';
				break;
			case '12':
				$mes = 'Diciembre';
				break;
			default:
				$mes = 'Enero';
		}
		return strtoupper($mes);
	}

	private function verificar_desertados(){
		if(session('user')->id_oferta==2 || session('user')->id_oferta==10){
			$otros_desertados = DB::select("select m.* from todosabc.matriculados m, todosabc.calificaciones_alfa k where k.id_usuario = :usid and m.id = k.id_matriculado and (k.modulo_1_desertado = true or k.modulo_2_desertado = true) and m.razon_desercion is null", ['usid' => session('user')->id]);
            return count($otros_desertados);
		}else if(session('user')->id_oferta==6 || session('user')->id_oferta==13 || session('user')->id_oferta==7 || session('user')->id_oferta==14){
          $otros_desertados = DB::select("select m.* from todosabc.matriculados m, todosabc.instituciones ie where ie.id = m.id_institucion and m.id in (select id_matriculado from todosabc.estado_participantes where estado = 'DESERTADO' and id_matriculado in (select m.id from todosabc.matriculados m, todosabc.instituciones ie where ie.id = m.id_institucion and ie.id_usuario = :ieid and m.asiste_con_frecuencia = :no and m.razon_desercion is null))", ['ieid' => session('user')->id, 'no' => true]);
          return count($otros_desertados);
      }else{
          $otros_desertados = DB::select("select m.* from todosabc.matriculados m, todosabc.instituciones ie, todosabc.docentes d where d.id = m.id_docente and ie.id = d.id_institucion and m.id in (select id_matriculado from todosabc.estado_participantes where estado = 'DESERTADO' and id_matriculado in (select m.id from todosabc.matriculados m, todosabc.instituciones ie where ie.id = m.id_institucion and ie.id_usuario = :ieid and m.asiste_con_frecuencia = :no and m.razon_desercion is null))", ['ieid' => session('user')->id, 'no' => true]);
          return count($otros_desertados);
       }
	}

	private function verificar_calificaciones(){
		$contador = 0;

		if(session('user')->id_oferta==2 || session('user')->id_oferta==10){
			return 0;
		}else if(session('user')->id_oferta==8 || session('user')->id_oferta==15){
			$matriculados = DB::select("select m.* from todosabc.matriculados m, todosabc.instituciones ie, todosabc.docentes d where ie.id_usuario = :idus and ie.id = d.id_institucion and d.id = m.id_docente and m.asiste_con_frecuencia = false", ['idus' => session('user')->id]);
			return count($matriculados);
		}else if(session('user')->id_oferta==6 || session('user')->id_oferta==13){
			$matriculados = DB::select("select m.* from todosabc.matriculados m, todosabc.instituciones ie where ie.id_usuario = :idus and ie.id = m.id_institucion and m.asiste_con_frecuencia = :si and m.razon_desercion is null", ['idus' => session('user')->id, 'si' => true]);

			foreach($matriculados as $mat){
				$desertado = DB::table('todosabc.estado_participantes')
				->where('id_matriculado', $mat->id)
				->where('estado', 'DESERTADO')
				->first();

				if($desertado!=null){
					continue;
				}

				$calificaciones = DB::select("select k.* from todosabc.calificaciones_basica k, todosabc.estado_participantes t where k.id_matriculado = t.id_matriculado and k.id_matriculado = :idmat and t.estado = 'PROMOVIDO'", ['idmat' => $mat->id]);
				
      			if(count($calificaciones) < 7){
      				$contador++;
      				break;
      			}				
			}
		}else if(session('user')->id_oferta==7 || session('user')->id_oferta==14){
			$matriculados = DB::select("select m.* from todosabc.matriculados m, todosabc.instituciones ie where ie.id_usuario = :idus and ie.id = m.id_institucion and m.asiste_con_frecuencia = :si and m.razon_desercion is null", ['idus' => session('user')->id, 'si' => true]);

			$fases = DB::table('todosabc.fases_oferta')
            ->where('id_oferta', session('user')->id_oferta)
            ->orderBy('id')
            ->get();

			foreach($matriculados as $mat){
				$desertado = DB::table('todosabc.estado_participantes')
				->where('id_matriculado', $mat->id)
				->where('estado', 'DESERTADO')
				->first();

				if($desertado!=null){
					continue;
				}

				if($mat->fase1==true){
					$calificaciones = DB::select("select k.* from todosabc.calificaciones_bachillerato k, todosabc.estado_participantes t where t.id_matriculado = k.id_matriculado and t.estado = 'PROMOVIDO' and k.id_matriculado = :idmat and k.id_oferta_fase = :idof", ['idmat' => $mat->id, 'idof' => $fases[0]->id]);
          			if(count($calificaciones) < 12){
          				$contador++;
          				break;
          			}
				}

				if($mat->fase2==true){
					$calificaciones = DB::select("select k.* from todosabc.calificaciones_bachillerato k, todosabc.estado_participantes t where t.id_matriculado = k.id_matriculado and t.estado = 'PROMOVIDO' and k.id_matriculado = :idmat and k.id_oferta_fase = :idof", ['idmat' => $mat->id, 'idof' => $fases[1]->id]);
          			if(count($calificaciones) < 12){
          				$contador++;
          				break;
          			}
				}

				if($mat->fase3==true){
					$calificaciones = DB::select("select k.* from todosabc.calificaciones_bachillerato k, todosabc.estado_participantes t where t.id_matriculado = k.id_matriculado and t.estado = 'PROMOVIDO' and k.id_matriculado = :idmat and k.id_oferta_fase = :idof", ['idmat' => $mat->id, 'idof' => $fases[2]->id]);
          			if(count($calificaciones) < 9){
          				$contador++;
          				break;
          			}
				}

				if($mat->fase1!=true && $mat->fase2!=true && $mat->fase3!=true){
					$contador++;
					break;
				}
			}
		}

		return $contador;
	}
}