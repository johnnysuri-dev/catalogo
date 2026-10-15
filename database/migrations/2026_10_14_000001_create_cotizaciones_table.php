<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('nombre_cliente', 150);
            $table->string('empresa', 120)->nullable();
            $table->string('correo', 150);
            $table->string('telefono', 30);
            $table->text('mensaje')->nullable();
            $table->string('moneda', 3)->default('USD');
            $table->decimal('total', 18, 2);
            $table->string('estado', 20)->default('pendiente');
            $table->foreignId('user_id')->nullable()->constrained('users', 'id_usuario')->nullOnDelete();
            $table->timestamps();
            $table->index(['estado', 'created_at']);
        });

        Schema::create('cotizacion_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
            $table->string('nombre_producto', 150);
            $table->string('sku', 60);
            $table->decimal('precio_unitario', 12, 2);
            $table->unsignedInteger('cantidad');
            $table->decimal('subtotal', 18, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizacion_detalles');
        Schema::dropIfExists('cotizaciones');
    }
};
