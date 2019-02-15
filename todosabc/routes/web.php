<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/customLogin', 'CustomAuthController@index')->name('customLogin');
Route::post('/customLogin', 'CustomAuthController@ingresar');
Route::post('/customLogout', 'CustomAuthController@salir')->name('customLogout');

/*
|--------------------------------------------------------------------------
| Routes de Instituciones
|--------------------------------------------------------------------------
*/
Route::get('/instituciones', 'InstitucionController@index')->name('institucionesUsuario');
Route::get('instituciones/nueva', 'InstitucionController@nueva')->name('nuevaIE');
Route::get('instituciones/id/{id}', 'InstitucionController@seleccionar');
Route::get('instituciones/seleccionar/{id}', 'InstitucionController@seleccionarIE');
Route::post('instituciones/modificar', 'InstitucionController@modificar')->name('modificarIE');
Route::post('instituciones/guardar', 'InstitucionController@guardar')->name('guardarIE');
Route::post('instituciones/limpiar', 'InstitucionController@limpiar')->name('limpiarMsgIE');
Route::post('instituciones/buscar', 'InstitucionController@buscar')->name('buscarAmie');
Route::post('instituciones/eliminar', 'InstitucionController@eliminar')->name('eliminarIE');

/*
|--------------------------------------------------------------------------
| Routes de Docentes
|--------------------------------------------------------------------------
*/
Route::get('/docentes', 'DocenteController@index')->name('docentesUsuario');
Route::get('docentes/nuevo', 'DocenteController@nuevo')->name('nuevoDocente');
Route::get('docentes/id/{id}', 'DocenteController@seleccionar');
Route::post('docentes/limpiar', 'DocenteController@limpiar')->name('limpiarMsgDoc');
Route::post('docentes/guardar', 'DocenteController@guardar')->name('guardarDoc');
Route::post('docentes/modificar', 'DocenteController@modificar')->name('modificarDoc');
Route::get('docentes/buscar/{id}', 'DocenteController@buscar');
Route::post('docentes/eliminar', 'DocenteController@eliminar')->name('eliminarDoc');

/*
|--------------------------------------------------------------------------
| Routes de Matriculados
|--------------------------------------------------------------------------
*/
Route::get('/matriculados', 'MatriculadoController@index')->name('matriculadosUsuario');
Route::get('matriculados/nuevo', 'MatriculadoController@nuevo')->name('nuevoMatriculado');
Route::get('matriculados/seleccionar/{opcion}/{id}', 'MatriculadoController@seleccionar');
Route::get('matriculados/certificados', 'MatriculadoController@certificado')->name('certificados');
Route::get('/matriculados_bachillerato', array('as' => 'matriculados_bachillerato', 'uses' => 'MatriculadoController@prebachillerato'));
Route::get('/matriculados_desertados', array('as' => 'matriculados_desertados', 'uses' => 'MatriculadoController@desertados'));
Route::post('matriculados/limpiar', 'MatriculadoController@limpiar')->name('limpiarMsgMat');
Route::post('matriculados/buscar', 'MatriculadoController@buscar')->name('buscarInscrito');
Route::post('matriculados/matricular', 'MatriculadoController@matricular')->name('matricularInscrito');
Route::post('matriculados/eliminar', 'MatriculadoController@eliminar')->name('eliminarMat');
Route::post('matriculados/modificar', 'MatriculadoController@modificar')->name('modificarMat');
Route::post('/matriculados_desertados', 'MatriculadoController@guardar_desertados')->name('matriculados_desertados');

/*
|--------------------------------------------------------------------------
| Routes de Calificaciones
|--------------------------------------------------------------------------
*/
Route::get('/calificaciones_alfa', array('as' => 'calificaciones_alfa', 'uses' => 'CalificacionController@alfa'));
Route::get('/calificaciones_prepost', array('as' => 'calificaciones_prepost', 'uses' => 'CalificacionController@prepost'));
Route::get('/calificaciones_post', array('as' => 'calificaciones_post', 'uses' => 'CalificacionController@post'));
Route::get('/calificaciones_prebasica', array('as' => 'calificaciones_prebasica','uses' => 'CalificacionController@prebasica'));
Route::get('/calificaciones_basica', array('as' => 'calificaciones_basica', 'uses' => 'CalificacionController@basica'));
Route::get('/calificaciones_prebachillerato', array('as'=>'calificaciones_prebachillerato','uses'=>'CalificacionController@prebachillerato'));
Route::get('/calificaciones_bachillerato', array('as'=>'calificaciones_bachillerato','uses'=>'CalificacionController@bachillerato'));
Route::post('calificaciones_alfa', 'CalificacionController@guardarAlfa')->name('guardarCalifAlfa');
Route::post('calificaciones_post', 'CalificacionController@guardarPost')->name('guardarCalifPost');
Route::post('calificaciones_basica', 'CalificacionController@guardarBasica')->name('guardarCalifBasica');
Route::post('bachillerato_fases', 'CalificacionController@bachillerato_fases')->name('bachilleratoFases');
Route::post('calificaciones_bachillerato', 'CalificacionController@guardarBachillerato')->name('guardarCalifBachillerato');

/*
|--------------------------------------------------------------------------
| Routes de Certificados
|--------------------------------------------------------------------------
*/
Route::get('/certificados/certificarPromocion', array('as'=>'certificar','uses'=>'CertificadoController@certificarPromocion'));
Route::get('/certificados/verificar', array('as' => 'verificar', 'uses' => 'CertificadoController@verificar'));
Route::post('/borrar_mensaje', array('as' => 'borrar_mensaje', 'uses' => 'CertificadoController@borrarMensaje'));