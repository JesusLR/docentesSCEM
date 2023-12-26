@extends('layouts.dashboard')

@section('template_title')
    Bienvenido {{ Auth::user()->name }}
@endsection

@section('header')
	TABLERO 
@endsection


@section('content')

	<h1>Home</h1>

@endsection
