<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $names = DB::table('productos')
            ->select('categoria')
            ->distinct()
            ->pluck('categoria')
            ->filter()
            ->unique();

        foreach ($names as $name) {
            if (! DB::table('categoria')->where('nombre_categoria', $name)->exists()) {
                DB::table('categoria')->insert([
                    'nombre_categoria' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('productos', function (Blueprint $table) {
            $table->dropIndex(['activo', 'categoria']);
            $table->foreignId('categoria_id')->nullable()->after('sku')->constrained('categoria')->restrictOnDelete();
        });

        foreach (DB::table('categoria')->get(['id', 'nombre_categoria']) as $categoria) {
            DB::table('productos')
                ->where('categoria', $categoria->nombre_categoria)
                ->update(['categoria_id' => $categoria->id]);
        }

        Schema::table('productos', function (Blueprint $table) {
            $table->index(['activo', 'categoria_id']);
            $table->dropColumn('categoria');
        });

        Schema::table('categoria', function (Blueprint $table) {
            $table->boolean('activo')->default(true);
            $table->unique('nombre_categoria');
        });
    }

    public function down(): void
    {
        Schema::table('categoria', function (Blueprint $table) {
            $table->dropUnique(['nombre_categoria']);
            $table->dropColumn('activo');
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->dropIndex(['activo', 'categoria_id']);
            $table->string('categoria', 80)->nullable();
        });

        foreach (DB::table('categoria')->get(['id', 'nombre_categoria']) as $categoria) {
            DB::table('productos')
                ->where('categoria_id', $categoria->id)
                ->update(['categoria' => $categoria->nombre_categoria]);
        }

        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
            $table->index(['activo', 'categoria']);
        });
    }
};
