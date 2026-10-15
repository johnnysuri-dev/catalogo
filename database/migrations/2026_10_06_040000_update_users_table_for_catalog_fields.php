<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id', 'id_usuario');
            $table->renameColumn('name', 'nombre');
            $table->renameColumn('email', 'correo');
            $table->renameColumn('password', 'password_hash');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('nombre', 100)->change();
            $table->string('correo', 100)->change();
            $table->enum('rol', ['Administrador', 'Vendedor', 'Cajero'])->default('Vendedor');
            $table->boolean('estado')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rol', 'estado']);
            $table->string('nombre')->change();
            $table->string('correo')->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id_usuario', 'id');
            $table->renameColumn('nombre', 'name');
            $table->renameColumn('correo', 'email');
            $table->renameColumn('password_hash', 'password');
        });
    }
};