@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success" style="text-align: center; font-size: 24px;">
                            Bienvenid@ {{ strtoupper(session('user')->apellidos) }} {{ strtoupper(session('user')->nombres) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
