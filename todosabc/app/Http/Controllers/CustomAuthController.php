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

			if($user->id_oferta==2 or $user->id_oferta==10){
				$paginas = DB::table('todosabc.urls')
		        ->where('id_perfil', $user->id_perfil)
		        ->where('activo', true)
		        ->where('nombre','<>','Calificaciones Post')
		        ->where('nombre','<>','Calificaciones Basica')
		        ->where('nombre','<>','Calificaciones Bachillerato')
		        ->get();
		    }else if($user->id_oferta==8 or $user->id_oferta==15){
				$paginas = DB::table('todosabc.urls')
		        ->where('id_perfil', $user->id_perfil)
		        ->where('activo', true)
		        ->where('nombre','<>','Calificaciones Alfa')
		        ->where('nombre','<>','Calificaciones Basica')
		        ->where('nombre','<>','Calificaciones Bachillerato')
		        ->get();
		    }else if($user->id_oferta==6 or $user->id_oferta==13){
				$paginas = DB::table('todosabc.urls')
		        ->where('id_perfil', $user->id_perfil)
		        ->where('activo', true)
		        ->where('nombre','<>','Calificaciones Alfa')
		        ->where('nombre','<>','Calificaciones Post')
		        ->where('nombre','<>','Calificaciones Bachillerato')
		        ->get();
		    }else if($user->id_oferta==7 or $user->id_oferta==14){
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