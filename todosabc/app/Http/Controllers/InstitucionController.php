<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Modelo\Institucion;
use App\Modelo\Cpl;
class InstitucionController extends Controller
{
    private $ies;
    private $amies;
    private $cpls;


    public function index()
    {
        $this->obtenerInstitucionesDelUsuario();
        $this->cargarAmiesUsuario();
        $this-> obtenerCplDelUsario();



        return view('menus.instituciones', ['ies' => $this->ies,
                                            'amies' => $this->amies,
                                            'cpls'=>$this->cpls,

                                           ]);
    }
    public function seleccionarIE(Request $request, $id){
        $ie = Institucion::find($id);
        $amie = DB::table('codigos_amie')
        ->where('amie', $ie->amie)
        ->where('zona', session('user')->zona)
        ->first();
        return view('instituciones.eliminar', ['ie' => $ie, 'institucion' => $amie->institucion]);
    }
    public function eliminar(Request $request){
        $ie = Institucion::find($request->input('id'));
        $docentes = DB::table('todosabc.docentes')
        ->where('id_institucion', $ie->id)
        ->get();
        $matriculados = DB::table('todosabc.matriculados')
        ->where('id_institucion', $ie->id)
        ->get();
        if(count($docentes)>0 or count($matriculados)>0){
            $msgIE = 'No se puede eliminar a la Institución Educativa '.$ie->amie.' porque tiene docentes o estudiantes vinculados en ella';
            $request->session()->put('estadoIE', 600);
            $request->session()->put('msgIE', $msgIE);
        }else{
            $ie->delete();
            $msgIE = 'Institución Educativa '.$ie->amie.' eliminada exitosamente';
            $request->session()->put('estadoIE', 200);
            $request->session()->put('msgIE', $msgIE);
        }
        return redirect()->route('institucionesUsuario');
    }
    public function buscar(Request $request)
    {
        $opcion = $request->input('accion');
        $codigo_amie = $request->input('cod_amie');
        if($codigo_amie){
            $amie = DB::table('codigos_amie')
            ->where('amie', $codigo_amie)
            ->where('zona', session('user')->zona)
            ->first();
            if($amie){
                if($opcion=='nue_ie'){
                    $msgIE = '';
                    $request->session()->put('estadoIE', 301);
                    $request->session()->put('msgIE', $msgIE);
                    $ie = new Institucion;
                    $ie->id_usuario = session('user')->id;
                    $ie->fecha_registro = date('d-m-Y h:i:s');
                    $ie->amie = $amie->amie;
                    $cpls = DB::table('todosabc.cpl')
                    ->join('codigos_amie','todosabc.cpl.zona','=','codigos_amie.zona')
                    ->where('todosabc.cpl.zona', session('user')->zona)
                    ->select('todosabc.cpl.id','todosabc.cpl.nombre')
                    ->groupby('todosabc.cpl.id','todosabc.cpl.nombre')
                    ->orderby('todosabc.cpl.nombre')
                     ->get();


                    if(session('user')->id_oferta==22 or
                     session('user')->id_oferta==23 or
                     session('user')->id_oferta==24 or
                     session('user')->id_oferta==25 or
                     session('user')->id_oferta==26 or
                     session('user')->id_oferta==27 or
                     session('user')->id_oferta==28 or
                     session('user')->id_oferta==29 or
                     session('user')->id_oferta==30 )
                    {
                        return view('instituciones.nueva', ['ie' => $ie, 'amie' => $amie,'cpls' =>$cpls]);
                    }
                    else {
                    return view('instituciones.nueva', ['ie' => $ie, 'amie' => $amie]);
                    }

                }else
                {
                    $msgIE = '';
                    $request->session()->put('estadoIE', 301);
                    $request->session()->put('msgIE', $msgIE);
                    $ie = Institucion::find($request->input('id_institucion'));
                    $ie->amie = $amie->amie;
                    $cpls=DB::table('todosabc.cpl')
                    ->select('todosabc.cpl.id','todosabc.cpl.nombre')
                    ->where('id',$ie->id_cpl)
                    ->get();

                    if(session('user')->id_oferta==22 or
                     session('user')->id_oferta==23 or
                     session('user')->id_oferta==24 or
                     session('user')->id_oferta==25 or
                     session('user')->id_oferta==26 or
                     session('user')->id_oferta==27 or
                     session('user')->id_oferta==28 or
                     session('user')->id_oferta==29 or
                     session('user')->id_oferta==30)
                    {
                        return view('instituciones.modificar', ['ie' => $ie, 'amie' => $amie,'cpls'=>$cpls]);
                    }
                    else {
                    return view('instituciones.modificar', ['ie' => $ie, 'amie' => $amie]);
                    }

                }
            }else{
                $msgIE = 'Debe ingresar un código AMIE válido';
                $request->session()->put('estadoIE', 500);
                $request->session()->put('msgIE', $msgIE);
                if($opcion=='nue_ie'){
                    return redirect()->route('nuevaIE');
                }else{
                    return redirect('instituciones/id/'.$request->input('id_institucion'));
                }
            }
        }else{
            $msgIE = 'Debe ingresar un código AMIE válido';
            $request->session()->put('estadoIE', 500);
            $request->session()->put('msgIE', $msgIE);
            if($opcion=='nue_ie'){
                return redirect()->route('nuevaIE');
            }else{
                return redirect('instituciones/id/'.$request->input('id_institucion'));
            }
        }
    }
    public function nueva(Request $request)
    {
        $this->limpiar($request);
        $ie = new Institucion;
        $ie->id_usuario = session('user')->id;
        $ie->fecha_registro = date('d-m-Y h:i:s');
        return view('instituciones.nueva', ['ie' => $ie, 'amie' => null]);
    }
    public function seleccionar(Request $request, $id)
    {
        $this->obtenerInstitucionesDelUsuario();
        $ie = Institucion::find($id);
        $amie = DB::table('codigos_amie')
        ->where('amie', $ie->amie)
        ->first();


                //$cpls = DB::table('todosabc.instituciones')
               // ->join('todosabc.cpl','todosabc.instituciones.id_cpl','=','todosabc.cpl.id')
               // ->select('todosabc.cpl.id','todosabc.cpl.nombre')
                //->get();
                    $cpls=DB::table('todosabc.cpl')
                    ->select('todosabc.cpl.id','todosabc.cpl.nombre')
                    ->where('id',$ie->id_cpl)
                    ->get();


                if(session('user')->id_oferta==22 or
                     session('user')->id_oferta==23 or
                     session('user')->id_oferta==24 or
                     session('user')->id_oferta==25 or
                     session('user')->id_oferta==26 or
                     session('user')->id_oferta==27 or
                     session('user')->id_oferta==28 or
                     session('user')->id_oferta==29 or
                     session('user')->id_oferta==30)
                {
                  return view('instituciones.modificar', ['ie' => $ie,
                                                        'amie' => $amie,
                                                        'amies' => $this->amies,
                                                        'cpls'=>$cpls,
                                                       ]);
              }
                else {
                    return view('instituciones.modificar', ['ie' => $ie,
                                                        'amie' => $amie,
                                                        'amies' => $this->amies,
                                                        ]);
        }
    }


    public function modificar(Request $request)
    {
        $ies_existente = DB::table('todosabc.instituciones')
        ->where('id_usuario', session('user')->id)
        ->get();

        if(count($ies_existente)>0){;
            $encontrado = false;
            foreach ($ies_existente as $ie) {
                if($ie->amie==$request->input('amie')){
                    $amie = DB::table('codigos_amie')
                    ->where('amie', $ies_existente[0]->amie)
                    ->where('zona', session('user')->zona)
                    ->first();
                    $ie_existente = $ie;
                    $encontrado = true;
                    break;
                }
            }
            if($encontrado){
                $msgIE = 'La Institución Educativa '.$ie_existente->amie.' ya se encuentra registrada';
                $request->session()->put('estadoIE', 600);
                $request->session()->put('msgIE', $msgIE);

                //$cpls = DB::table('todosabc.instituciones')
               // ->join('todosabc.cpl','todosabc.instituciones.id_cpl','=','todosabc.cpl.id')
               // ->select('todosabc.cpl.id','todosabc.cpl.nombre')
                //->get();

           $cpls=DB::table('todosabc.cpl')
                  ->select('todosabc.cpl.id','todosabc.cpl.nombre')
                  ->where('id',$request->input('id_cpl'))
                 ->get();


                if(session('user')->id_oferta==22 or
                     session('user')->id_oferta==23 or
                     session('user')->id_oferta==24 or
                     session('user')->id_oferta==25 or
                     session('user')->id_oferta==26 or
                     session('user')->id_oferta==27 or
                     session('user')->id_oferta==28 or
                     session('user')->id_oferta==29 or
                     session('user')->id_oferta==30){


                return view('instituciones.modificar', ['ie' => $ie_existente, 'amie' => $amie,'cpls'=>$cpls]);

                }
                else{
               return view('instituciones.modificar', ['ie' => $ie_existente, 'amie' => $amie]);
                }

            }
        }

        if(session('user')->id_oferta==22 or
                     session('user')->id_oferta==23 or
                     session('user')->id_oferta==24 or
                     session('user')->id_oferta==25 or
                     session('user')->id_oferta==26 or
                     session('user')->id_oferta==27 or
                     session('user')->id_oferta==28 or
                     session('user')->id_oferta==29 or
                     session('user')->id_oferta==30)
        {

        $cpl_existe=DB::table('todosabc.cpl')
                ->select('todosabc.cpl.nombre')
                ->where('id',$request->input('id_cpl'))
                ->get();

        $institucion = Institucion::find($request->input('id_institucion'));
        $institucion->amie = $request->input('amie');
        $institucion->id_cpl=$request->input('id_cpl');
        $institucion->nombre_cpl=$cpl_existe[0]->nombre;
        $institucion->update();

        $msgIE = "Los datos de la Institución educativa fueron actualizados exitosamente";
        $request->session()->put('estadoIE', 200);
        $request->session()->put('msgIE', $msgIE);

        }
        else{
        $institucion = Institucion::find($request->input('id_institucion'));
        $institucion->amie = $request->input('amie');
        $institucion->update();
        $msgIE = "Los datos de la Institución educativa fueron actualizados exitosamente";
        $request->session()->put('estadoIE', 200);
        $request->session()->put('msgIE', $msgIE);

        }


        return redirect()->route('institucionesUsuario');
    }
    public function guardar(Request $request)
    {
        $ies_existente = DB::table('todosabc.instituciones')
        ->where('id_usuario', session('user')->id)
        ->get();
        if(count($ies_existente)>0){;
            $encontrado = false;
            foreach ($ies_existente as $ie) {
                if($ie->amie==$request->input('selectAmie')){
                    $amie = DB::table('codigos_amie')
                    ->where('amie', $ies_existente[0]->amie)
                    ->where('zona', session('user')->zona)
                    ->first();
                    $ie_existente = $ie;
                    $encontrado = true;
                    break;
                }
            }
            if($encontrado){
                $msgIE = 'La Institución Educativa '.$ie_existente->amie.' ya se encuentra registrada';
                $request->session()->put('estadoIE', 600);
                $request->session()->put('msgIE', $msgIE);
                return view('instituciones.nueva', ['ie' => $ie_existente, 'amie' => $amie]);
            }
        }

        $cpl_existe=DB::table('todosabc.cpl')
        ->select('todosabc.cpl.nombre')
        ->where('id',$request->input('id_cpl'))
        ->get();

        if(session('user')->id_oferta==22 or
                     session('user')->id_oferta==23 or
                     session('user')->id_oferta==24 or
                     session('user')->id_oferta==25 or
                     session('user')->id_oferta==26 or
                     session('user')->id_oferta==27 or
                     session('user')->id_oferta==28 or
                     session('user')->id_oferta==29 or
                     session('user')->id_oferta==30)
        {

        $ie = new Institucion;
        $ie->id_usuario = session('user')->id;
        $ie->amie = $request->input('selectAmie');
        $ie->fecha_registro = date('d-m-Y h:i:s');
        $ie->id_cpl= $request->input('id_cpl');
        $ie->nombre_cpl=$cpl_existe[0]->nombre;
        $ie->save();
        $msgIE = "Institución educativa ".$ie->amie." registrada exitosamente ";
        $request->session()->put('estadoIE', 200);
        $request->session()->put('msgIE', $msgIE);
        return redirect()->route('institucionesUsuario');
        }
        else{
          $ie = new Institucion;
        $ie->id_usuario = session('user')->id;
        $ie->amie = $request->input('selectAmie');
        $ie->fecha_registro = date('d-m-Y h:i:s');
        $ie->save();
        $msgIE = "Institución educativa ".$ie->amie." registrada exitosamente ";
        $request->session()->put('estadoIE', 200);
        $request->session()->put('msgIE', $msgIE);
        return redirect()->route('institucionesUsuario');
        }

    }

    public function limpiar(Request $request)
    {
        $request->session()->put('estadoIE', 301);
        $request->session()->put('msgIE', '');
        return redirect()->route('institucionesUsuario');
    }
    private function cargarMenus()
    {
        $this->paginas = DB::table('todosabc.urls')
        ->where('id_perfil', session('user')->id_perfil)
        ->where('activo', true)
        ->get();
    }
    private function obtenerInstitucionesDelUsuario()
    {
        $this->ies = DB::table('todosabc.instituciones')
        ->where('id_usuario', session('user')->id)
        ->orderBy('id', 'desc')
        ->get();
    }


    private function obtenerCplDelUsario()
        {
           $this->cpls = DB::table('todosabc.cpl')
                    ->join('codigos_amie','todosabc.cpl.zona','=','codigos_amie.zona')
                    ->where('todosabc.cpl.zona', session('user')->zona)
                    ->select('todosabc.cpl.nombre')
                    ->groupby('todosabc.cpl.id','todosabc.cpl.nombre')
                    ->orderby('todosabc.cpl.nombre')
                     ->get();

    }


 private function cargarCplUsuario()
    {
        $cpls = DB::table('todosabc.instituciones')
                    ->join('todosabc.cpl','todosabc.cpl.id','=','todosabc.instituciones.id_cpl')
                    ->where('todosabc.cpl.zona', session('user')->zona)
                    ->select('todosabc.cpl.nombre')
                    ->groupby('todosabc.cpl.id','todosabc.cpl.nombre')
                    ->orderby('todosabc.cpl.nombre')
                     ->get();
         if($cpls){
            foreach ($cpls as $key => $value) {
                $cpls[$value->nombre] = $cpls[$key];
                unset($key);
            }
            $this->cpls = $cpls;
        }
    }




    private function cargarAmiesUsuario()
    {
        $codigos = [];
        for ($i = 0; $i < $this->ies->count(); $i++) {
            $codigos[$i] = $this->ies[$i]->amie;
        }
        $amies = DB::table('codigos_amie')
        ->whereIn('amie', $codigos)
        ->get();

         if($amies){
            foreach ($amies as $key => $value) {
                $amies[$value->amie] = $amies[$key];
                unset($key);
            }
            $this->amies = $amies;
        }
    }

}