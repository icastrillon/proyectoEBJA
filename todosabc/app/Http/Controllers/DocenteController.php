<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Modelo\Docente;

class DocenteController extends Controller
{
	private $docentes;
    private $ies;

    public function index()
    {
        $this->cargarInstitucionesDeUsuario();

        if($this->ies->count()==0){
            return redirect()->route('institucionesUsuario');
        }

        $this->cargarDocentesDeUsuario();
    	return view('menus.docentes', ['docentes' => $this->docentes]);
    }

    public function limpiar(Request $request)
    {
        $request->session()->put('estadoDoc', 301);
        $request->session()->put('msgDoc', '');
        return redirect()->route('docentesUsuario');
    }

    public function buscar($id){
        $doc = Docente::find($id);
        return view('docentes.eliminar', ['doc' => $doc]);
    }

    public function eliminar(Request $request){
        $id_docente = $request->input('id');
        if(isset($id_docente)) { 
            $docente = Docente::find($id_docente);
            $docente->delete();
            $msgDoc = "Docente eliminado/a exitosamente";
            $request->session()->put('estadoDoc', 200);
            $request->session()->put('msgDoc', $msgDoc);          
            return redirect()->route('docentesUsuario');
        }
    }

    public function modificar(Request $request)
    {
        $apellidos = $request->input('apellidos');
        $nombres = $request->input('nombres');
        $email = $request->input('email');
        $telefono = $request->input('telefono');

        if(session('user')->id_oferta!=2 and ($apellidos==null or $nombres==null or $email==null or $telefono==null)){
            $request->session()->put('estadoDoc', 500);
            $msgDoc = 'Los campos con (*) son obligatorios';
            $request->session()->put('msgDoc', $msgDoc);
            return redirect()->route('nuevoDocente');
        }

    	$id_docente = $request->input('id_docente');
    	$si_tiene_voluntario = $request->input('si_tiene_voluntario');
    	$no_tiene_voluntario = $request->input('no_tiene_voluntario');
        $docente = Docente::find($id_docente);

        if(session('user')->id_oferta==2){
        	if(($si_tiene_voluntario!='' and $no_tiene_voluntario!='') or ($si_tiene_voluntario=='' and $no_tiene_voluntario=='')){
        		$request->session()->put('estadoDoc', 500);
        		$msgDoc = 'A la pregunta acerca del Voluntario debe responder SI o NO';
            	$request->session()->put('msgDoc', $msgDoc);
            	return redirect('docentes/id/'.$id_docente);
        	}else{
                if($si_tiene_voluntario){
                    $docente->tiene_voluntario = true;
                }
                if($no_tiene_voluntario){
                    $docente->tiene_voluntario = false;
                }
            }
        }else if(session('user')->id_oferta==10  )
        {
            $docente->clasificacion = $request->input('clasificacion');
        }

        else if(session('user')->id_oferta==16  or session('user')->id_oferta==17)       
        {
            $docente->clasificacion = $request->input('clasificacion');
        }

		$this->cargarInstitucionesDeUsuario();
		$docente->email = $email;
    	$docente->telefono = $telefono;

        if(session('user')->id_oferta!=2 and session('user')->id_oferta!=8 and session('user')->id_oferta!=9){
            $docente->apellidos = $apellidos;
            $docente->nombres = $nombres;                
        }

    	$docente->update();

    	$request->session()->put('estadoDoc', 200);
		$msgDoc = 'Los datos del docente fueron actualizados exitosamente';
		$request->session()->put('msgDoc', $msgDoc);
		return redirect()->route('docentesUsuario');
    }

    public function guardar(Request $request)
    {
        $identificacion = $request->input('cedula');
        $apellidos = $request->input('apellidos');
        $nombres = $request->input('nombres');
        $email = $request->input('email');
        $telefono = $request->input('telefono');
        $id_institucion = $request->input('id_institucion');

        $docente_existente = DB::table('todosabc.docentes')
        ->where('cedula',$identificacion)
        ->where('id_institucion',$id_institucion)
        ->get();

        if($docente_existente->count()>0){
            $request->session()->put('estadoDoc', 500);
            $msgDoc = 'El docente con identificación'.$identificacion.' ya está registrado';
            $request->session()->put('msgDoc', $msgDoc);
            return redirect()->route('nuevoDocente');
        }

        if(session('user')->id_oferta!=2 and ($identificacion==null or $apellidos==null or $nombres==null or $email==null or $telefono==null)){
            $request->session()->put('estadoDoc', 500);
            $msgDoc = 'Los campos con (*) son obligatorios';
            $request->session()->put('msgDoc', $msgDoc);
            return redirect()->route('nuevoDocente');
        }

    	$si_tiene_voluntario = $request->input('si_tiene_voluntario');
    	$no_tiene_voluntario = $request->input('no_tiene_voluntario');
        $docente = new Docente;

        if(session('user')->id_oferta==2){
        	if(($si_tiene_voluntario!='' and $no_tiene_voluntario!='') or ($si_tiene_voluntario=='' and $no_tiene_voluntario=='')){
        		$request->session()->put('estadoDoc', 500);
        		$msgDoc = 'A la pregunta acerca del Voluntario debe responder SI o NO';
            	$request->session()->put('msgDoc', $msgDoc);
            	return redirect()->route('nuevoDocente');
        	}else{
                if($si_tiene_voluntario){
                    $docente->tiene_voluntario = true;
                }
                if($no_tiene_voluntario){
                    $docente->tiene_voluntario = false;
                }
            }
        }

        else if((session('user')->id_oferta==10) or (session('user')->id_oferta==17)){
            $docente->clasificacion = $request->input('clasificacion');
        }


        else if(session('user')->id_oferta==16)       
        {
            $docente->clasificacion = $request->input('clasificacion');
        }



		$this->cargarInstitucionesDeUsuario();
    	$docente->id_institucion = $id_institucion;
    	$docente->fecha_registro = date('Y-m-d h:m:s');

        if(session('user')->id_oferta!=2){
            $docente->nombres = $nombres;
            $docente->apellidos = $apellidos;
            $docente->cedula = $identificacion;
        } else {
	    	$docente->nombres = session('user')->nombres;
	    	$docente->apellidos = session('user')->apellidos;
	    	$docente->cedula = session('user')->usuario;
        }

    	$docente->activo = true;
    	$docente->email = $email;
    	$docente->telefono = $telefono;

    	$docente->save();

    	$request->session()->put('estadoDoc', 200);
		$msgDoc = 'El registro del docente fue exitoso';
		$request->session()->put('msgDoc', $msgDoc);
		return redirect()->route('docentesUsuario');
    }

    public function nuevo(Request $request)
    {
    	$this->cargarInstitucionesDeUsuario();
        $this->cargarDocentesDeUsuario();
        $id_institucion = $request->input('id_institucion');

    	$docente = new Docente;
    	$docente->id_institucion = $id_institucion;
        
        $ie = DB::table('todosabc.instituciones')
        ->join('codigos_amie', 'todosabc.instituciones.amie', '=', 'codigos_amie.amie')
        ->select('todosabc.instituciones.*', 'codigos_amie.institucion', 'codigos_amie.zona')
        ->where('todosabc.instituciones.id', $id_institucion)
        ->first();

        if(count($this->docentes)==0){
            $docente->nombres = session('user')->nombres;
            $docente->apellidos = session('user')->apellidos;
            $docente->cedula = session('user')->usuario;
        }

        return view('docentes.nuevo', ['docente' => $docente, 
                                       'ie' => $ie,
                                       'ies' => $this->ies,
                                       ]);
    }

    public function seleccionar(Request $request, $id)
    {
    	$docente = Docente::find($id);

        $ie = DB::table('todosabc.instituciones')
        ->join('codigos_amie', 'todosabc.instituciones.amie', '=', 'codigos_amie.amie')
        ->select('todosabc.instituciones.*', 'codigos_amie.institucion', 'codigos_amie.zona')
        ->where('todosabc.instituciones.id', $docente->id_institucion)
        ->first();

        $this->cargarInstitucionesDeUsuario();

    	return view('docentes.modificar', ['docente' => $docente,
                                           'ie' => $ie,
                                           'ies' => $this->ies,
                                           ]);
    }

    private function cargarInstitucionesDeUsuario()
    {
    	$this->ies = DB::table('todosabc.instituciones')
    	->join('codigos_amie', 'todosabc.instituciones.amie', '=', 'codigos_amie.amie')
    	->select('todosabc.instituciones.*', 'codigos_amie.institucion', 'codigos_amie.zona')
    	->where('id_usuario', session('user')->id)
    	->get();
    }

    private function cargarDocentesDeUsuario()
    {
    	$this->docentes = DB::table('todosabc.docentes')
    	->join('todosabc.instituciones', 'todosabc.docentes.id_institucion', '=', 'todosabc.instituciones.id')
    	->join('codigos_amie', 'todosabc.instituciones.amie', '=', 'codigos_amie.amie')
    	->select('todosabc.docentes.*', 'codigos_amie.institucion', 'codigos_amie.zona')
    	->where('todosabc.instituciones.id_usuario', session('user')->id)
    	->orderBy('todosabc.docentes.id', 'desc')
    	->get();
    }
}
