<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('catalogos', function (Blueprint $table) {
            $table->boolean('sob_consulta')->default(false)->after('desconto_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalogos', function (Blueprint $table) {
            $table->dropColumn('sob_consulta');
        });
    }
};
