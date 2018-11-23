<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Modelo\Inscrito;
use App\Modelo\Matriculado;
use App\Modelo\Oferta;

class MatriculadoController extends Controller
{
	private $matriculados;
	private $inscritos;
	private $oferta;
    private $ies;
    private $docentes;
    private $generos;
    private $estados_civiles;
    private $etnias;
    private $situaciones_laborales;
    private $actividades_economicas;
    private $datos_familiares;
    private $nacionalidades;
    private $rezagos_educativos;
    private $ultimos_anios_aprobados;
    private $ofertas_educativas;
    private $zonas;
    private $campos_obligaotorios;
    private $lugares_atencion;
    private $estudiantes;
    private $desertados;

    public function index()
    {
      $this->cargarInstitucionesDelUsuario();
      if($this->ies == null){
          return redirect()->route('institucionesUsuario');
      }

      $this->cargarDocentesDeUsuario();
      if($this->docentes == null or $this->docentes->count() == 0){
          return redirect()->route('docentesUsuario');
      }

    	$this->cargarMatriculados();
    	$this->cargarOfertaUsuario();

    	return view('menus.matriculados', ['matriculados' => $this->matriculados,
    									                   'oferta' => $this->oferta,
    									                   ]);
    }

    public function desertados(Request $request){
        $this->cargarEstudiantesDesertados();
        view()->share(['desertados' => $this->desertados]);
        return view('menus.desertados');
    }

    public function guardar_desertados(Request $request){
        $this->cargarEstudiantesDesertados();
        foreach ($this->desertados as $d) {
            $codmat = $d->codigo_inscripcion;
            $mat = Matriculado::find($d->id);
            $mat->razon_desercion = $request->input('rd-'.$codmat);
            $mat->update();
        }
  
        return redirect()->route('matriculados_desertados');
    }

    public function prebachillerato(Request $request){
        $matriculados = DB::select('select m.*, ca.amie, ca.institucion from todosabc.matriculados m, todosabc.instituciones ie, codigos_amie ca where ie.id = m.id_institucion and ie.amie = ca.amie and ie.id_usuario = :ieid and m.asiste_con_frecuencia = :acf order by m.nombres_aspirantes', ['ieid' => session('user')->id, 'acf' => true]);
        view()->share(['matriculados' => $matriculados]);
        return view('calificaciones.pres.prebachillerato');
    }

    public function certificado(Request $request)
    {
        $this->cargarOfertaUsuario();
        $this->cargarEstudiantes();

        $parametro = DB::table('cat_parametros_app')
        ->where('nombre','CERTIFICADOS_TODOS_ABC')
        ->first();

        $descarga_visible = explode(',', $parametro->valor);

        view()->share(['matriculados' => $this->estudiantes,
                     'oferta' => $this->oferta,
                     'descarga_visible' => $descarga_visible,
                    ]);

        return view('menus.certificados');
    }

    public function modificar(Request $request)
    {
        $matriculado = Matriculado::find($request->input('id_matriculado'));
        $si_asiste_con_frecuencia = $request->input('si_asiste_con_frecuencia');
        $no_asiste_con_frecuencia = $request->input('no_asiste_con_frecuencia');

        if(($si_asiste_con_frecuencia!='' and $no_asiste_con_frecuencia!='') or ($si_asiste_con_frecuencia=='' and $no_asiste_con_frecuencia=='')){
            $request->session()->put('estadoMat', 500);
            $msgMat = 'A la pregunta acerca de la asistencia del participante debe responder Asiste con frecuencia o Desertó';
            $request->session()->put('msgMat', $msgMat);
            return redirect('matriculados/seleccionar/modificar/'.$request->input('id_matriculado'));
        }else{
            if($si_asiste_con_frecuencia){
                $matriculado->asiste_con_frecuencia = true;
                $matriculado->razon_desercion = null;
            }
            if($no_asiste_con_frecuencia){
                $matriculado->asiste_con_frecuencia = false;
            }
        
            if($this->validarDatos($matriculado, $request, 'modificar')){
                $matriculado->update();
                $msgMat = 'Los datos de '.$matriculado->nombres_aspirantes.' fueron actualizados exitosamente';
                $request->session()->put('estadoMat', 200);
                $request->session()->put('msgMat', $msgMat);
                return redirect()->route('matriculadosUsuario');
            }else{
                $msgMat = 'Los siguientes campos: '.$this->campos_obligaotorios.' son obligatorios';
                $request->session()->put('estadoMat', 500);
                $request->session()->put('msgMat', $msgMat);
                return redirect('matriculados/seleccionar/modificar/'.$request->input('id_matriculado'));
            }  
        }      
    }

    public function eliminar(Request $request)
    {
        $id_matriculado = $request->input('id');
        $codigo = $request->input('cod');
        if(isset($id_matriculado)) { 
            $inscrito = Inscrito::where('codigo_inscripcion', $codigo)->first();
            $inscrito->estado = '1';
            $inscrito->update();
            DB::table('todosabc.matriculados')
            ->where('id', $id_matriculado)
            ->delete();
            $msgMat = "Participante eliminado/a exitosamente";
            $request->session()->put('estadoMat', 200);
            $request->session()->put('msgMat', $msgMat);          
            return redirect()->route('matriculadosUsuario');
        }
    }

    public function matricular(Request $request)
    {
        $inscrito = Inscrito::find($request->input('id_inscrito'));
        $matriculado = new Matriculado;
        $si_asiste_con_frecuencia = $request->input('si_asiste_con_frecuencia');
        $no_asiste_con_frecuencia = $request->input('no_asiste_con_frecuencia');

        if(($si_asiste_con_frecuencia!='' and $no_asiste_con_frecuencia!='') or ($si_asiste_con_frecuencia=='' and $no_asiste_con_frecuencia=='')){
            $request->session()->put('estadoMat', 500);
            $msgMat = 'A la pregunta acerca de la asistencia del participante debe responder Asiste con frecuencia o Desertó';
            $request->session()->put('msgMat', $msgMat);
            return redirect('matriculados/seleccionar/inscrito/'.$request->input('id_inscrito'));
        }else{
            if($si_asiste_con_frecuencia){
                $matriculado->asiste_con_frecuencia = true;
            }
            if($no_asiste_con_frecuencia){
                $matriculado->asiste_con_frecuencia = false;
            }

            if($this->validarDatos($matriculado, $request, null)){
                $inscrito->estado = 'MATRICULADO';
                $inscrito->save();
                $matriculado->save();
                $msgMat = $matriculado->nombres_aspirantes.' fue matriculado/a exitosamente';
                $request->session()->put('estadoMat', 200);
                $request->session()->put('msgMat', $msgMat);
                return redirect()->route('nuevoMatriculado');
            }else{
                $msgMat = 'Los siguientes campos: '.$this->campos_obligaotorios.' son obligatorios';
                $request->session()->put('estadoMat', 500);
                $request->session()->put('msgMat', $msgMat);
                return redirect('matriculados/seleccionar/inscrito/'.$request->input('id_inscrito'));
            } 
        }       
    }

    public function nuevo(Request $request)
    {
    	$this->cargarOfertaUsuario();
    	return view('matriculados.buscar', ['oferta' => $this->oferta]);
    }

    public function buscar(Request $request)
    {
        $zona = '';
        $encontrados = [];
        $msgMat = '';
    	  $identificacion = $request->input('txtIdentificacion');
        $apellidos = $request->input('txtApellidos');
        $this->cargarOfertaUsuario();

    	if($identificacion != null){
            $ids_ofertas = array();
            
            if(session('user')->id_oferta==15){
              array_push($ids_ofertas, 11);
              array_push($ids_ofertas, 12);
      
            }
            else if(session('user')->id_oferta==8){
              array_push($ids_ofertas, 3);
              array_push($ids_ofertas, 4);
              array_push($ids_ofertas, 5);

            }// OFERTAS PARA id=19
          else if(session('user')->id_oferta==19){
              array_push($ids_ofertas, 11);
              array_push($ids_ofertas, 12);
               array_push($ids_ofertas,16);
               array_push($ids_ofertas,17);
           }


              else{
              array_push($ids_ofertas, session('user')->id_oferta);
            }

            $matriculados = DB::table('todosabc.matriculados')
            ->join('todosabc.ofertas', 'todosabc.ofertas.id', '=', 'todosabc.matriculados.id_oferta')
            ->select('todosabc.matriculados.*', 'todosabc.ofertas.nombre as oferta')
            ->where('cedula_identidad', trim($identificacion))
            ->whereIn('todosabc.matriculados.id_oferta', $ids_ofertas)
            ->get();

            if(count($matriculados)>0){
                $msgMat = 'El estudiante '.$matriculados[0]->nombres_aspirantes. ' con Identificación '.$matriculados[0]->cedula_identidad. ' ya está MATRICULADO en la oferta educativa: '.$matriculados[0]->oferta.', en la '.$matriculados[0]->nom_zona;
                $request->session()->put('estadoMat', 250);
                $request->session()->put('msgMat', $msgMat);
                return redirect()->route('nuevoMatriculado');
            }

            $historico = DB::table('historico')
            ->join('oferta_educativa', 'oferta_educativa.id', '=', 'historico.id_oferta_educativa')
            ->join('zona', 'historico.id_zona', '=', 'zona.id')
            ->select('historico.participante', 'historico.cedula_participante', 'historico.anio', 'oferta_educativa.nombre as oferta', 'zona.nombre as zona')
            ->where('historico.cedula_participante', trim($identificacion))
            ->where('historico.id_estado_participante', 2)
            ->first();

            $participante = DB::table('participante')
            ->join('oferta_educativa', 'oferta_educativa.id', '=', 'participante.id_oferta_educativa')
            ->join('funcionario', 'participante.id_usuario', '=', 'funcionario.id')
            ->join('zona', 'funcionario.id_zona', '=', 'zona.id')
            ->select('participante.nombre', 'participante.cedula', 'participante.anio', 'oferta_educativa.nombre as oferta', 'zona.nombre as zona')
            ->where('participante.id_estado_participante', 2)
            ->where('participante.cedula', trim($identificacion))
            ->where('participante.id_oferta_educativa', '<', 6)
            ->where('funcionario.id_perfil', '<>', 3)
            ->first();

            if($participante){
                array_push($encontrados, ['nombres' => $participante->nombre,
                                          'identificacion' => $participante->cedula,
                                          'oferta' => $participante->oferta,
                                          'zona' => $participante->zona,
                                          'anio' => $participante->anio,
                                         ]);
            } else if($historico){
                array_push($encontrados, ['nombres' => $historico->participante,
                                          'identificacion' => $historico->cedula_participante,
                                          'oferta' => $historico->oferta,
                                          'zona' => $historico->zona,
                                          'anio' => $historico->anio,
                                         ]);
            }

            $this->inscritos = DB::table('todosabc.inscritos')
            ->where('cedula_identidad', trim($identificacion))
            ->where('nom_zona', session('user')->zona)
            ->where('estado', '<>', 'MATRICULADO')
            ->orderBy('id', 'desc')
            ->get();

            if($this->inscritos->count()>0){
                $zona = $this->inscritos[0]->nom_zona;
            }

        }else if(isset($apellidos) and $apellidos != ''){
            $this->inscritos = DB::table('todosabc.inscritos')
            ->where('nombres_aspirantes', 'like', strtoupper($apellidos).'%')  
            ->where('nom_zona', session('user')->zona) 
            ->where('estado', '<>', 'MATRICULADO')    
            ->get();
        }else{
            $request->session()->put('estadoMat', 301);
            $request->session()->put('msgMat', $msgMat);
            return redirect()->route('nuevoMatriculado');
        }

        if($this->inscritos->count() > 0 and count($encontrados) == 0){
            $request->session()->put('estadoMat', 301);
            $request->session()->put('msgMat', $msgMat);
            return view('matriculados.buscar', ['oferta' => $this->oferta,
                                                'inscritos' => $this->inscritos]);
        } else if($this->inscritos->count() > 0 and count($encontrados) > 0){
            $request->session()->put('estadoMat', 400);
            $request->session()->put('msgMat', $msgMat);
            return view('matriculados.buscar', ['oferta' => $this->oferta,
                                                'inscritos' => $this->inscritos,
                                                'encontrados' => $encontrados,
                                               ]);
        } else {
            $parametro = ($identificacion != null) ? $identificacion : $apellidos;            
            $msgMat = 'El/La ciudadano/a '.$parametro. ' no se encuentra en los Inscritos de la '.session('user')->zona.' del Proyecto EBJA. Debe comunicarse con Planta Central para verificar su inscripción.';           
            $request->session()->put('estadoMat', 600);
            $request->session()->put('msgMat', $msgMat);
            return redirect()->route('nuevoMatriculado');
        }
    }

    public function seleccionar(Request $request, $opcion, $id)
    {
        $this->cargarCombos();
        $paralelos = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O'];
    	if($opcion=='modificar'){
            $matriculado = Matriculado::find($id);
            $this->cargarInstitucionesDelUsuario();
            if(session('user')->id_oferta==2 or session('user')->id_oferta==10 ){
                $this->cargarDocentesDeUsuario();
                return view('matriculados.modificar', ['matriculado' => $matriculado,
                                                       'ies' => $this->ies,
                                                       'docentes' => $this->docentes,
                                                       'generos' => $this->generos,
                                                       'estados_civiles' => $this->estados_civiles,
                                                       'etnias' => $this->etnias,
                                                       'situaciones_laborales' => $this->situaciones_laborales,
                                                       'actividades_economicas' => $this->actividades_economicas,
                                                       'datos_familiares' => $this->datos_familiares,
                                                       'nacionalidades' => $this->nacionalidades,
                                                       'rezagos_educativos' => $this->rezagos_educativos,
                                                       'ultimos_anios_aprobados' => $this->ultimos_anios_aprobados,
                                                      // 'ultimo_anio_aprobado' =>  
                                                    //   $matriculado->ultimo_anio_aprobado, 
                                                       'ofertas_educativas' => $this->ofertas_educativas,
                                                       'zonas' => $this->zonas,
                                                       'paralelo' => $matriculado->paralelo,
                                                       'paralelos' => $paralelos,
                                                       ]);
            }
            else if(session('user')->id_oferta==8 or session('user')->id_oferta==15 or session('user')->id_oferta==19 or session('user')->id_oferta==16 or session('user')->id_oferta==17 )
            {
                $this->cargarDocentesDeUsuario();
                return view('matriculados.modificar', ['matriculado' => $matriculado,
                                                       'ies' => $this->ies,
                                                       'docentes' => $this->docentes,
                                                       'generos' => $this->generos,
                                                       'estados_civiles' => $this->estados_civiles,
                                                       'etnias' => $this->etnias,
                                                       'situaciones_laborales' => $this->situaciones_laborales,
                                                       'actividades_economicas' => $this->actividades_economicas,
                                                       'datos_familiares' => $this->datos_familiares,
                                                       'nacionalidades' => $this->nacionalidades,
                                                    'rezagos_educativos' => $this->rezagos_educativos,
                                                   'ultimos_anios_aprobados' => $this->ultimos_anios_aprobados,
                                                      // 'ultimo_anio_aprobado' =>  
                                                      // $matriculado->ultimo_anio_aprobado, 
                                                       'ofertas_educativas' => $this->ofertas_educativas,
                                                       'zonas' => $this->zonas,
                                                       'paralelo' => $matriculado->paralelo,
                                                       'paralelos' => $paralelos,
                                                       'id_docente' => $this->docentes[0]->id,
                                                       'lugar' => $matriculado->lugar_atencion_especial,
                                                       'lugares_atencion' => $this->lugares_atencion,
                                                       'id_institucion' => $matriculado->id_institucion,
                                                       ]);
            }else{
                return view('matriculados.modificar', ['matriculado' => $matriculado,
                                                      'ies' => $this->ies,
                                                      'generos' => $this->generos,
                                                      'estados_civiles' => $this->estados_civiles,
                                                      'etnias' => $this->etnias,
                                                      'situaciones_laborales' => $this->situaciones_laborales,
                                                      'actividades_economicas' => $this->actividades_economicas,
                                                      'datos_familiares' => $this->datos_familiares,
                                                      'nacionalidades' => $this->nacionalidades,
                                                      'rezagos_educativos' => $this->rezagos_educativos,
                                                   //    'ultimo_anio_aprobado' =>  
                                                     //  $matriculado->ultimo_anio_aprobado, 
                                                      'ultimos_anios_aprobados' => $this->ultimos_anios_aprobados,
                                                      'ofertas_educativas' => $this->ofertas_educativas,
                                                      'zonas' => $this->zonas,
                                                      'paralelo' => $matriculado->paralelo,
                                                      'paralelos' => $paralelos,
                                                      ]);
            }
    	}else if($opcion=='eliminar'){
            $mat = Matriculado::find($id);
            return view('matriculados.eliminar', ['mat' => $mat]);
    	}else if($opcion=='inscrito'){
            $inscrito = Inscrito::find($id);
            $this->cargarInstitucionesDelUsuario();
            if(session('user')->id_oferta==2 or session('user')->id_oferta==10 ){
                $this->cargarDocentesDeUsuario();
                return view('matriculados.inscrito', ['inscrito' => $inscrito,
                                                      'ies' => $this->ies,
                                                      'docentes' => $this->docentes,
                                                      'generos' => $this->generos,
                                                      'estados_civiles' => $this->estados_civiles,
                                                      'etnias' => $this->etnias,
                                                      'situaciones_laborales' => $this->situaciones_laborales,
                                                      'actividades_economicas' => $this->actividades_economicas,
                                                      'datos_familiares' => $this->datos_familiares,
                                                      'nacionalidades' => $this->nacionalidades,
                                                      'rezagos_educativos' => $this->rezagos_educativos,
                                                   //    'ultimo_anio_aprobado' =>'',
                                                      'ultimos_anios_aprobados' => $this->ultimos_anios_aprobados,
                                                      'ofertas_educativas' => $this->ofertas_educativas,
                                                      'zonas' => $this->zonas,
                                                      'id_docente' => $this->docentes[0]->id,
                                                      'paralelo' => '',
                                                      'paralelos' => $paralelos,
                                                      ]);
            }else if(session('user')->id_oferta==8 or session('user')->id_oferta==15 or
             session('user')->id_oferta==17 or session('user')->id_oferta==19 or session('user')->id_oferta==16 )
                        {
                $this->cargarDocentesDeUsuario();
                return view('matriculados.inscrito', ['inscrito' => $inscrito,
                                                      'ies' => $this->ies,
                                                      'docentes' => $this->docentes,
                                                      'generos' => $this->generos,
                                                      'estados_civiles' => $this->estados_civiles,
                                                      'etnias' => $this->etnias,
                                                      'situaciones_laborales' => $this->situaciones_laborales,
                                                      'actividades_economicas' => $this->actividades_economicas,
                                                      'datos_familiares' => $this->datos_familiares,
                                                      'nacionalidades' => $this->nacionalidades,
                                                      'rezagos_educativos' => $this->rezagos_educativos,
                                                      'ultimos_anios_aprobados' => $this->ultimos_anios_aprobados,
                                                    //  'ultimo_anio_aprobado' =>'',
                                                      'ofertas_educativas' => $this->ofertas_educativas,
                                                      'zonas' => $this->zonas,
                                                      'id_docente' => $this->docentes[0]->id,
                                                      'paralelo' => '',
                                                      'paralelos' => $paralelos,
                                                      'lugar' => '',
                                                      'lugares_atencion' => $this->lugares_atencion,
                                                      'id_institucion' => '',
                                                      ]);
            }else{
                return view('matriculados.inscrito', ['inscrito' => $inscrito,
                                                      'ies' => $this->ies,
                                                      'generos' => $this->generos,
                                                      'estados_civiles' => $this->estados_civiles,
                                                      'etnias' => $this->etnias,
                                                      'situaciones_laborales' => $this->situaciones_laborales,
                                                      'actividades_economicas' => $this->actividades_economicas,
                                                      'datos_familiares' => $this->datos_familiares,
                                                      'nacionalidades' => $this->nacionalidades,
                                                      'rezagos_educativos' => $this->rezagos_educativos,
                                                      'ultimos_anios_aprobados' => $this->ultimos_anios_aprobados,
                                                    //   'ultimo_anio_aprobado' =>'',
                                                      'ofertas_educativas' => $this->ofertas_educativas,
                                                      'zonas' => $this->zonas,
                                                      'paralelo' => '',
                                                      'paralelos' => $paralelos,
                                                      ]);
            }
        }
    }

    public function limpiar(Request $request)
    {
    	$request->session()->put('estadoMat', 301);
        return redirect()->route('matriculadosUsuario');
    }

    private function validarDatos(Matriculado $matriculado, Request $request, $opcion)
    {
        $datos_validos = true;
        $matriculado->codigo_inscripcion = $request->input('codigo_inscripcion');
        $matriculado->fecha_inscripcion = $request->input('fecha_inscripcion');
        $matriculado->estado = 'MATRICULADO';
        $matriculado->email = $request->input('email');
        $matriculado->telefono_celular = $request->input('telefono_celular');
        $matriculado->fecha_matriculacion = date('Y-m-d h:m.s');

        if($request->input('tipo_documento_identidad')!='-1'){
            $matriculado->tipo_documento_identidad = $request->input('tipo_documento_identidad');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Tipo de Identificación,';
            $datos_validos = false;
        }

        if($request->input('tipo_documento_identidad')!='CEDULA' and $request->input('tipo_documento_identidad')!='NO PRESENTA'){
            if($request->input('cedula_identidad')!=''){
                $matriculado->cedula_identidad = $request->input('cedula_identidad');
            }else{
                $this->campos_obligaotorios = 'Identificación,';
                $datos_validos = false;
            }
        }else {
            $matriculado->cedula_identidad = $request->input('cedula_identidad');
        }

        if($request->input('nombres_aspirantes')!=''){
            $matriculado->nombres_aspirantes = $request->input('nombres_aspirantes');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Nombres,';
            $datos_validos = false;
        }

        if($request->input('fecha_nacimiento')!=''){
            if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->input('fecha_nacimiento'))){
                $matriculado->fecha_nacimiento= $request->input('fecha_nacimiento');
            }else{
                $this->campos_obligaotorios = $this->campos_obligaotorios.'Fecha de Nacimiento no tiene el formato correcto,';
                $datos_validos = false;
            }
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Fecha de Nacimiento,';
            $datos_validos = false;
        }

        if($request->input('genero')!='-1'){
            $matriculado->genero = $request->input('genero');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Género,';
            $datos_validos = false;
        }

        if($request->input('estado_civil')!='-1'){
            $matriculado->estado_civil = $request->input('estado_civil');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Estado Civil,';
            $datos_validos = false;
        }

        if($request->input('etnia')!='-1'){
            $matriculado->etnia= $request->input('etnia');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Etnia,';
            $datos_validos = false;
        }

        if($request->input('situacion_laboral')!='-1'){
            $matriculado->situacion_laboral = $request->input('situacion_laboral');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Situación Laboral,';
            $datos_validos = false;
        }

        if($request->input('actividad_economica')!='-1'){
            $matriculado->actividad_economica = $request->input('actividad_economica');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Actividad Económica,';
            $datos_validos = false;
        }

        if($request->input('datos_familiares')!='-1'){
            $matriculado->datos_familiares = $request->input('datos_familiares');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Datos Familiares,';
            $datos_validos = false;
        }

        if($request->input('nacionalidad')!='-1'){
            $matriculado->nacionalidad = $request->input('nacionalidad');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Nacionalidad,';
            $datos_validos = false;
        }

        if($request->input('rezago_educativo')!='-1'){
            $matriculado->rezago_educativo = $request->input('rezago_educativo');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Rezago Educativo,';
            $datos_validos = false;
        }

        if($request->input('ultimo_anio_aprobado')!='-1'){
            $matriculado->ultimo_anio_aprobado = $request->input('ultimo_anio_aprobado');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Último año aprobado,';
            $datos_validos = false;
        }

        if($request->input('nom_zona')!='-1'){
            $matriculado->nom_zona = $request->input('nom_zona');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Zona,';
            $datos_validos = false;
        }

        if($request->input('nom_provincia')!=''){
            $matriculado->nom_provincia = $request->input('nom_provincia');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Provincia,';
            $datos_validos = false;
        }

        if($request->input('nom_canton')!=''){
            $matriculado->nom_canton = $request->input('nom_canton');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Cantón,';
            $datos_validos = false;
        }

        if($request->input('nom_parroquia')!=''){
            $matriculado->nom_parroquia = $request->input('nom_parroquia');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Parroquia,';
            $datos_validos = false;
        }

        if($request->input('direccion')!=''){
            $matriculado->direccion = $request->input('direccion');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Dirección,';
            $datos_validos = false;
        }

        if(session('user')->id_oferta !=2 and session('user')->id_oferta !=10 and $request->input('paralelo')!='-1'){
            $matriculado->paralelo = $request->input('paralelo');
        }else if(session('user')->id_oferta !=2 and $request->input('paralelo')=='-1'){
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Paralelo,';
            $datos_validos = false;
        }

        if($request->input('oferta_educativa')!='-1'){
            $matriculado->id_oferta = $request->input('oferta_educativa');
        }else{
            $this->campos_obligaotorios = $this->campos_obligaotorios.'Oferta Educativa,';
            $datos_validos = false;
        }

        if($opcion!='modificar')
        {//si es nuevo
            if(session('user')->id_oferta==2 or session('user')->id_oferta==10   )
            {
                $matriculado->id_docente = $request->input('id_docente');
            }

                        
            else if(session('user')->id_oferta==8 or session('user')->id_oferta==15 or  session('user')->id_oferta==19 or session('user')->id_oferta==16  or session('user')->id_oferta==17)
            {            

                if($request->input('lugar')=='-1'){
                    $this->campos_obligaotorios = $this->campos_obligaotorios.'Lugar de Atención,';
                    $datos_validos = false;
                }else if($request->input('id_institucion')=='-1'){
                    $this->campos_obligaotorios = $this->campos_obligaotorios.'Institución,';
                    $datos_validos = false;
                }else{
                  $matriculado->id_docente = $request->input('id_docente');
                  $matriculado->id_institucion = $request->input('id_institucion');
                  $matriculado->lugar_atencion_especial = $request->input('lugar');
                }
            }
            else {
                $matriculado->id_institucion = $request->input('id_institucion');
            }
        }

        else{//si ya existe
            if(session('user')->id_oferta==8 or session('user')->id_oferta==15 or   session('user')->id_oferta==19 or session('user')->id_oferta==16  or session('user')->id_oferta==17){
                if($request->input('lugar')=='-1'){
                    $this->campos_obligaotorios = $this->campos_obligaotorios.'Lugar de Atención,';
                    $datos_validos = false;
                }else if($request->input('id_institucion')=='-1'){
                    $this->campos_obligaotorios = $this->campos_obligaotorios.'Institución,';
                    $datos_validos = false;
                }else{
                  $matriculado->id_docente = $request->input('id_docente');
                  $matriculado->id_institucion = $request->input('id_institucion');
                  $matriculado->lugar_atencion_especial = $request->input('lugar');
                }
            }
        }

        return $datos_validos;
    }

    private function cargarOfertaUsuario()
    {
    	$this->oferta = DB::table('todosabc.ofertas')
    	->where('id', session('user')->id_oferta)
    	->first();
    }

    private function cargarMatriculados()
    {
    	$this->cargarInstitucionesDelUsuario();
      if(session('user')->id_oferta==2 or session('user')->id_oferta==10 ){
          $ids = [];
          for ($i = 0; $i < $this->ies->count(); $i++) {
              $ids[$i] = $this->ies[$i]->id;
          }

          $docente = DB::table('todosabc.docentes')
          ->whereIn('id_institucion', $ids)
          ->first();

          if($docente)
          {
              $this->matriculados = DB::table('todosabc.matriculados')
              ->join('todosabc.docentes', 'todosabc.docentes.id', '=', 'todosabc.matriculados.id_docente')
              ->join('todosabc.instituciones', 'todosabc.instituciones.id', '=', 'todosabc.docentes.id_institucion')
              ->join('codigos_amie', 'todosabc.instituciones.amie', '=', 'codigos_amie.amie')
              ->join('todosabc.ofertas', 'todosabc.matriculados.id_oferta', '=', 'todosabc.ofertas.id')
              ->select('todosabc.matriculados.*', 'todosabc.instituciones.amie', 'codigos_amie.institucion', 'todosabc.ofertas.nombre as oferta_educativa')
              ->where('todosabc.matriculados.id_docente', $docente->id)
              ->orderBy('todosabc.matriculados.id', 'desc')
              ->get();
          }  	
      }else if(session('user')->id_oferta==8 or session('user')->id_oferta==15  or  session('user')->id_oferta==19 or session('user')->id_oferta==16  or session('user')->id_oferta==17){
          $ids = [];
          for ($i = 0; $i < $this->ies->count(); $i++) {
              $ids[$i] = $this->ies[$i]->id;
          }

          $docente = DB::table('todosabc.docentes')
          ->whereIn('id_institucion', $ids)
          ->first();

          if($docente){
              $this->matriculados = DB::table('todosabc.matriculados')
              ->join('todosabc.ofertas', 'todosabc.matriculados.id_oferta', '=', 'todosabc.ofertas.id')
              ->join('todosabc.instituciones', 'todosabc.matriculados.id_institucion', '=', 'todosabc.instituciones.id')
              ->join('codigos_amie', 'todosabc.instituciones.amie', '=', 'codigos_amie.amie')
              ->select('todosabc.matriculados.*', 'todosabc.instituciones.amie', 'codigos_amie.institucion', 'todosabc.ofertas.nombre as oferta_educativa')
              ->whereIn('todosabc.matriculados.id_institucion', $ids)
              ->orderBy('todosabc.matriculados.id', 'desc')
              ->get();
          }
      }else{
              $this->matriculados = DB::table('todosabc.matriculados')
              ->join('todosabc.instituciones', 'todosabc.instituciones.id', '=', 'todosabc.matriculados.id_institucion')
              ->join('codigos_amie', 'todosabc.instituciones.amie', '=', 'codigos_amie.amie')
              ->join('todosabc.ofertas', 'todosabc.matriculados.id_oferta', '=', 'todosabc.ofertas.id')
              ->select('todosabc.matriculados.*', 'todosabc.instituciones.amie', 'codigos_amie.institucion', 'todosabc.ofertas.nombre as oferta_educativa')
              ->where('todosabc.matriculados.id_institucion', $this->ies[0]->id)
              ->where('todosabc.instituciones.id_usuario', session('user')->id)
              ->orderBy('id', 'desc')
              ->get();
      }        
    }

    private function cargarInstitucionesDelUsuario()
    {
        $this->ies = DB::table('todosabc.instituciones')
        ->join('codigos_amie', 'codigos_amie.amie', '=', 'todosabc.instituciones.amie')
        ->select('todosabc.instituciones.*', 'codigos_amie.institucion as institucion')
        ->where('todosabc.instituciones.id_usuario', session('user')->id)
        ->get();
    }

    private function cargarDocentesDeUsuario(){
        if($this->ies){
            $ids = [];
            for ($i = 0; $i < $this->ies->count(); $i++) {
                $ids[$i] = $this->ies[$i]->id; 
            }

            $this->docentes = DB::table('todosabc.docentes')
            ->join('todosabc.instituciones', 'todosabc.instituciones.id', '=', 'todosabc.docentes.id_institucion')
            ->join('codigos_amie', 'todosabc.instituciones.amie', '=', 'codigos_amie.amie')
            ->select('todosabc.docentes.*','todosabc.instituciones.amie', 'codigos_amie.institucion')
            ->whereIn('id_institucion', $ids)
            ->get();
        }
    }

    private function cargarCombos(){
        $this->actividades_economicas = DB::table('actividad_economica')
        ->orderBy('nombre')
        ->get();

        $this->situaciones_laborales = DB::table('todosabc.inscritos')
        ->groupBy('situacion_laboral')
        ->select('situacion_laboral')
        ->orderBy('situacion_laboral')
        ->get();

        $this->datos_familiares = DB::table('todosabc.inscritos')
        ->groupBy('datos_familiares')
        ->select('datos_familiares')
        ->orderBy('datos_familiares')
        ->get();

        $this->etnias = DB::table('todosabc.inscritos')
        ->groupBy('etnia')
        ->select('etnia')
        ->orderBy('etnia')
        ->get();

        $this->nacionalidades = DB::table('todosabc.inscritos')
        ->groupBy('nacionalidad')
        ->select('nacionalidad')
        ->orderBy('nacionalidad')
        ->get();

        $this->rezagos_educativos = DB::table('todosabc.inscritos')
        ->groupBy('rezago_educativo')
        ->select('rezago_educativo')
        ->orderBy('rezago_educativo')
        ->get();

       $this->ultimos_anios_aprobados = DB::table('todosabc.inscritos')
       ->groupBy('ultimo_anio_aprobado')
       ->select('ultimo_anio_aprobado')
         ->orderBy('ultimo_anio_aprobado')
       ->get();

        if(session('user')->id_oferta==8){
            $this->ofertas_educativas = DB::table('todosabc.ofertas')
            ->whereIn('id', [3,4,5])
            ->orderBy('nombre')
            ->get();
        }else if(session('user')->id_oferta==9){
            $this->ofertas_educativas = DB::table('todosabc.ofertas')
            ->whereIn('id', [2,3,4,5])
            ->orderBy('nombre')
            ->get();
        }else if(session('user')->id_oferta==15){
            $this->ofertas_educativas = DB::table('todosabc.ofertas')
            ->whereIn('id', [11,12])
            ->orderBy('nombre')
            ->get();    

                       
        }else if(session('user')->id_oferta==19){
            $this->ofertas_educativas = DB::table('todosabc.ofertas')
            ->whereIn('id', [17,16])
            ->orderBy('nombre')
            ->get();    
        }
        
        else {
            $this->ofertas_educativas = $this->oferta;
        }

        $this->estados_civiles = DB::table('todosabc.inscritos')
        ->groupBy('estado_civil')
        ->select('estado_civil')
        ->orderBy('estado_civil')
        ->get();

        $this->zonas = DB::table('zona')
        ->where('activo', true)
        ->orderBy('nombre')
        ->get();

        $this->generos = DB::table('genero')
        ->orderBy('nombre')
        ->get();

        $this->lugares_atencion = [
                                   'Domicilio',
                                   'Institucion Educativa',
                                   'Centro de Rehabilitación Social (CRS)',
                                   'Centro de Adolescentes Infractores (CAI)',
                                  ];

       // $this->ultimos_anios_aprobados = [              
         //                          'Ninguno',
           //                         '1 EBG',
             //                       '2 EGB',
               //                     '3 EGB',
                 //                   '4 EGB',
                   //                 '5 EGB',
                     //               '6 EGB',
                       //             '7 EGB',
                         //           '8 EGB',
                           //         '9 EGB',
                             //       '10 EGB',
                               //     '1 BGU',
                                 //   '2 BGU',
                                   // '3 BGU - PRUEBA',
                                  //];

          }

    private function cargarEstudiantes(){
      if(session('user')->id_oferta==6 || session('user')->id_oferta==7 || session('user')->id_oferta==13 || session('user')->id_oferta==14  || session('user')->id_oferta==20 || session('user')->id_oferta==21){
          $this->estudiantes = DB::select('select m.*, oe.nombre as oferta_educativa, ca.amie, ca.institucion, t.estado as estado from todosabc.matriculados m, todosabc.instituciones ie, codigos_amie ca, todosabc.ofertas oe, todosabc.estado_participantes t where t.id_matriculado = m.id and ie.id = m.id_institucion and ie.amie = ca.amie and oe.id = m.id_oferta and ie.id_usuario = :ieid order by m.nombres_aspirantes', ['ieid' => session('user')->id]);
      }else{
          $this->estudiantes = DB::select('select m.*, oe.nombre as oferta_educativa, ca.amie, ca.institucion from todosabc.matriculados m, todosabc.instituciones ie, codigos_amie ca, todosabc.ofertas oe, todosabc.docentes d where ie.id_usuario = :ieid and ie.id = d.id_institucion and ie.amie = ca.amie and d.id = m.id_docente and oe.id = m.id_oferta order by m.nombres_aspirantes', ['ieid' => session('user')->id]);
      }
    }

    private function cargarEstudiantesDesertados(){
      if(session('user')->id_oferta==6 || session('user')->id_oferta==7 || session('user')->id_oferta==13 || session('user')->id_oferta==14 || session('user')->id_oferta==20 || session('user')->id_oferta==21 ){
          $this->desertados = DB::select('select m.*, ca.amie, ca.institucion from todosabc.matriculados m, todosabc.instituciones ie, codigos_amie ca where ie.id = m.id_institucion and ie.amie = ca.amie and ie.id_usuario = :ieid and m.asiste_con_frecuencia = :no order by m.nombres_aspirantes', ['ieid' => session('user')->id, 'no' => false]);

          $otros_desertados = DB::select("select m.*, ca.amie, ca.institucion from todosabc.matriculados m, todosabc.instituciones ie, codigos_amie ca where ie.id = m.id_institucion and ie.amie = ca.amie and m.id in (select id_matriculado from todosabc.estado_participantes where estado = 'DESERTADO' and id_matriculado in (select m.id from todosabc.matriculados m, todosabc.instituciones ie where ie.id = m.id_institucion and ie.id_usuario = :ieid and m.asiste_con_frecuencia = :no)) order by m.nombres_aspirantes", ['ieid' => session('user')->id, 'no' => true]);

          if(count($otros_desertados) > 0){
            foreach ($otros_desertados as $od) {
                array_push($this->desertados, $od);
            }
          }
      }else{
          $this->desertados = DB::select('select m.*, ca.amie, ca.institucion from todosabc.matriculados m, todosabc.instituciones ie, todosabc.docentes d, codigos_amie ca where d.id = m.id_docente and ie.id = d.id_institucion and ie.amie = ca.amie and ie.id_usuario = :ieid and m.asiste_con_frecuencia = :no order by m.nombres_aspirantes', ['ieid' => session('user')->id, 'no' => false]);

          if(session('user')->id_oferta==8 || session('user')->id_oferta==15 || session('user')->id_oferta==19 || session('user')->id_oferta==16 || session('user')->id_oferta==17 ){
              $otros_desertados = DB::select("select m.*, ca.amie, ca.institucion from todosabc.matriculados m, todosabc.instituciones ie, todosabc.docentes d, codigos_amie ca where d.id = m.id_docente and ie.id = d.id_institucion and ie.amie = ca.amie and m.id in (select id_matriculado from todosabc.estado_participantes where estado = 'DESERTADO' and id_matriculado in (select mm.id from todosabc.matriculados mm, todosabc.docentes dd, todosabc.instituciones i where dd.id = mm.id_docente and i.id = dd.id_institucion and i.id_usuario = :ieid and mm.asiste_con_frecuencia = :no)) order by mm.nombres_aspirantes", ['ieid' => session('user')->id, 'no' => true]);

              if(count($otros_desertados) > 0){
                foreach ($otros_desertados as $od) {
                    array_push($this->desertados, $od);
                }
              }
          }else if(session('user')->id_oferta==2 || session('user')->id_oferta==10 ){
                $otros_desertados = DB::select("select m.*, ca.amie, ca.institucion from todosabc.matriculados m, todosabc.instituciones ie, todosabc.docentes d, codigos_amie ca where d.id = m.id_docente and ie.id = d.id_institucion and ie.amie = ca.amie and m.id in (select k.id_matriculado from todosabc.calificaciones_alfa k where (k.modulo_1_desertado = :si or k.modulo_2_desertado = :si) and k.id_matriculado in (select mm.id from todosabc.matriculados mm, todosabc.instituciones i, todosabc.docentes dd where i.id = dd.id_institucion and dd.id = mm.id_docente and i.id_usuario = :ieid and m.asiste_con_frecuencia = :si)) order by m.nombres_aspirantes", ['ieid' => session('user')->id, 'si' => true]);

              if(count($otros_desertados) > 0){
                foreach ($otros_desertados as $od) {
                    array_push($this->desertados, $od);
                }
              }
          }
       }
    }
}
