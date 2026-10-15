@extends('adminlte::page')

@section('title', 'Nuevo cliente')

@section('content_header')
    <h1>Nuevo cliente</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('clientes.store') }}" method="POST">
                @csrf
                @include('clientes._form')
            </form>
        </div>
    </div>
@stop