@extends('adminlte::page')

@section('title', 'Editar cliente')

@section('content_header')
    <h1>Editar cliente</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                @csrf
                @method('PUT')
                @include('clientes._form')
            </form>
        </div>
    </div>
@stop