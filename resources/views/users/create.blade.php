@extends('adminlte::page')

@section('title', 'Nuevo usuario')

@section('content_header')
    <h1>Nuevo usuario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                @include('users._form')
            </form>
        </div>
    </div>
@stop