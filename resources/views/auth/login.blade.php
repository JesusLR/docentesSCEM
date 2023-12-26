@extends('layouts.auth')

@section('template_title')
    Entrar
@endsection

@section('content')

   <div class="container">
        <div class="row">
            <div class="col m2 l3 xl4"></div>

            <div class="col s12 m8 l6 xl4">
                {!! Form::open(array('action' => 'LoginController@auth', 'method' => 'POST','role' => 'form')) !!}
                {{ csrf_field() }}
                <div class="card">
                    <div class="card-content">
                        <center><img src="{{ asset('images/logo.png') }}" width="40%" height="40%"></center> 
                        <span class="card-title center-align">SISTEMA DE CONTROL ESCOLAR MODELO</span>
                        <span class="card-title center-align">DOCENTES</span>
                    </div>
                    <div class="row">
                        <div class="col s1"></div>
                        <div class="input-field col s10 {{ $errors->has('empleado_id') ? 'is-invalid' :'' }}">
                            <i class="material-icons prefix">person</i>
                            {!! Form::text('empleado_id', null, array('id' => 'empleado_id', 'class' => 'validate' )) !!}
                            {!! Form::label('empleado_id', 'Usuario'); !!}
                        </div>
                        <div class="col s1"></div>
                    </div>
                    <div class="row">
                        <div class="col s1"></div>
                        <div class="input-field col s10 {{ $errors->has('password') ? 'is-invalid' :'' }}">
                            <i id="showpassword" style="cursor:pointer" class="material-icons prefix">https</i>
                            <i id="hidepassword" style="cursor:pointer" class="material-icons prefix hide">no_encryption</i>
                            {!! Form::password('password', array('id' => 'userpass', 'class' => 'validate')) !!}
                            {!! Form::label('password', 'Contraseña'); !!}
                            <span class="helper-text" data-error="El campo es requerido"></span>
                        </div> 
                        <div class="col s1"></div>
                    </div>  
                    <div class="row">
                        <div class="col s1"></div>
                        <div class="col s10">
                            {!! Form::button('Entrar
                            <div class="spinner-layer spinner-green">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div>
                                <div class="gap-patch">
                                    <div class="circle"></div>
                                </div>
                                <div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>', array('class' => 'btn btn-large waves-effect waves-light buton-12  darken-4','type' => 'submit','id' => 'submit')) !!}                            
                        </div> 
                        <div class="col s1"></div>
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
            <div class="col m2 l3 xl4"></div>
        </div>
    </div>

@endsection


@section('footer_scripts')
   
@endsection