@extends('adminlte::page')

@section('title', 'Nuevo proveedor')

@section('content_header')
    <h1>Nuevo proveedor</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('proveedores.store') }}" method="POST">
                @csrf
                @include('proveedores._form')
            </form>
        </div>
    </div>
@stop