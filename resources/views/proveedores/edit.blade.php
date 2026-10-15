@extends('adminlte::page')

@section('title', 'Editar proveedor')

@section('content_header')
    <h1>Editar proveedor</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('proveedores.update', $proveedor) }}" method="POST">
                @csrf
                @method('PUT')
                @include('proveedores._form')
            </form>
        </div>
    </div>
@stop