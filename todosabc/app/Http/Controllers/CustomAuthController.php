<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CustomAuthController extends Controller
{

	public function index()
	{
		return view('customLogin', ['status' => 0]);
	}


	public function ingresar(Request $request)
	{
		$users = DB::table('todosabc.usuarios')
		->where('usuario', $request->usuario)
		->where('clave', $request->clave)
		->get();

		$user = null;
		$contador = 0;

		foreach ($users as $u) {
			if($u->activo==true){
				$user = $u;
				break;
			}else{
				$contador++;
			}
		}

		if($contador > 0 and $contador == $users->count()){
			return view('customLogin', ['status' => 301]);
		}

		if($user){
			$oferta = DB::table('todosabc.ofertas')
			->where('id', $user->id_oferta)
			->first();

			if($user->id_oferta==2 or $user->id_oferta==10 or $user->id_oferta==16 or $user->id_oferta==17 or  $user->id_oferta==22 or  $user->id_oferta==23 ){
				$paginas = DB::table('todosabc.urls')
		        ->where('id_perfil', $user->id_perfil)
		        ->where('activo', true)
		        ->where('nombre','<>','Calificaciones Post')
		        ->where('nombre','<>','Calificaciones Basica')
		        ->where('nombre','<>','Calificaciones Bachillerato')
		        ->get();
		    }else if($user->id_oferta==8 or $user->id_oferta==15 or $user->id_oferta==19 or $user->id_oferta==24){
				$paginas = DB::table('todosabc.urls')
		        ->where('id_perfil', $user->id_perfil)
		        ->where('activo', true)
		        ->where('nombre','<>','Calificaciones Alfa')
		        ->where('nombre','<>','Calificaciones Basica')
		        ->where('nombre','<>','Calificaciones Bachillerato')
		        ->get();
		    }else if($user->id_oferta==6 or $user->id_oferta==13 or $user->id_oferta==20  or $user->id_oferta==25){
				$paginas = DB::table('todosabc.urls')
		        ->where('id_perfil', $user->id_perfil)
		        ->where('activo', true)
		        ->where('nombre','<>','Calificaciones Alfa')
		        ->where('nombre','<>','Calificaciones Post')
		        ->where('nombre','<>','Calificaciones Bachillerato')
		        ->get();
		    }else if($user->id_oferta==7 or $user->id_oferta==14 or $user->id_oferta==21 or  $user->id_oferta==26){
				$paginas = DB::table('todosabc.urls')
		        ->where('id_perfil', $user->id_perfil)
		        ->where('activo', true)
		        ->where('nombre','<>','Calificaciones Alfa')
		        ->where('nombre','<>','Calificaciones Post')
		        ->where('nombre','<>','Calificaciones Basica')
		        ->get();
			}else {
				$paginas = [];
			}

			session(['status' => 200,
			  'paginas' => $paginas,
			  'user' => $user,
			  'oferta' => $oferta,
			  'estadoIE' => 404,
			  'msgIE' => '',
			  'estadoMat' => 404,
			  'msgMat' => '',
			  'estadoDOc' => 404,
			  'token' => md5($user->usuario),
			  'msgDoc' => '',
			]);

			return view('home');
		}else{
			return view('customLogin', ['status' => 400]);
		}
	}

	public function salir(Request $request)
	{
		$request->session()->flush();
		return redirect('/');
	}
}