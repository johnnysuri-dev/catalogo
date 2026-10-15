<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('sku', 60)->unique();
            $table->string('categoria', 80);
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 12, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->index(['activo', 'categoria']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
