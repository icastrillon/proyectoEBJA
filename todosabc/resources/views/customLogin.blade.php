@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Credenciales</div>

                <div class="panel-body">
                    @if ($status==400)
                        <div class="alert alert-danger">
                            Credenciales incorrectas
                        </div>
                    @elseif ($status==301)
                        <div class="alert alert-warning">
                            Su usuario se encuentra deshabilitado, comuníquese con el administrador.
                        </div>
                    @endif
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('customLogin') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('usuario') ? ' has-error' : '' }}">
                            <label for="usuario" class="col-md-4 control-label">Usuario</label>

                            <div class="col-md-6">
                                <input id="usuario" type="text" class="form-control" name="usuario" value="{{ old('usuario') }}" required autofocus>

                                @if ($errors->has('usuario'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('usuario') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('clave') ? ' has-error' : '' }}">
                            <label for="clave" class="col-md-4 control-label">Clave</label>

                            <div class="col-md-6">
                                <input id="clave" type="password" class="form-control" name="clave" required>

                                @if ($errors->has('clave'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('clave') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Ingresar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
