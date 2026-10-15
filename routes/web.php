<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [App\Http\Controllers\CatalogoController::class, 'index'])->name('catalogo.index');
Route::post('/cotizaciones', [App\Http\Controllers\CatalogoController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('catalogo.store');

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/cotizaciones', [App\Http\Controllers\CotizacionController::class, 'index'])->name('cotizaciones.index');
    Route::get('/cotizaciones/{cotizacion}', [App\Http\Controllers\CotizacionController::class, 'show'])->name('cotizaciones.show');
    Route::patch('/cotizaciones/{cotizacion}', [App\Http\Controllers\CotizacionController::class, 'update'])->name('cotizaciones.update');

    Route::resource('productos', App\Http\Controllers\ProductoController::class)
        ->except(['show'])
        ->parameters(['productos' => 'producto']);

    Route::resource('categorias', App\Http\Controllers\CategoriaController::class)
        ->except(['show'])
        ->parameters(['categorias' => 'categoria']);

    Route::resource('users', UserController::class)
        ->middleware('can:manage-users')
        ->parameters(['users' => 'user']);

    Route::resource('clientes', App\Http\Controllers\ClienteController::class)
        ->parameters(['clientes' => 'cliente']);

    Route::resource('proveedores', App\Http\Controllers\ProveedorController::class)
        ->parameters(['proveedores' => 'proveedor']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
