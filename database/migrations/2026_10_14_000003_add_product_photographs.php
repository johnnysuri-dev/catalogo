<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->json('fotos')->nullable()->after('descripcion');
        });

        Schema::table('cotizacion_detalles', function (Blueprint $table) {
            $table->string('fotografia', 255)->nullable()->after('sku');
        });
    }

    public function down(): void
    {
        Schema::table('cotizacion_detalles', function (Blueprint $table) {
            $table->dropColumn('fotografia');
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('fotos');
        });
    }
};
